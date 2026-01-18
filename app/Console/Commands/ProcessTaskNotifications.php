<?php

namespace App\Console\Commands;

use App\Mail\TaskExpiredNotification;
use App\Models\Task;
use App\Models\TaskReminder;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProcessTaskNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:process-notifications {--dry-run : Run without actually sending emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process expired tasks and send email notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->newLine();
        }
        // Step 1: Find and process expired tasks
        $createdReminders = $this->findAndCreateReminders();

        // Step 2: Send pending notifications
        $sentCount = $this->sendPendingNotifications($isDryRun);

        // Summary
        $this->displaySummary($createdReminders, $sentCount);

        return Command::SUCCESS;
    }

    /**
     * Find expired tasks and create reminders
     */
    protected function findAndCreateReminders(): int
    {
        $expiredTasks = Task::query()
            ->where('due_date', '<=', now())
            ->whereNull('completed_at')
            ->whereDoesntHave('taskReminder', function ($query) {
                $query->whereNotNull('send_at');
            })
            ->with(['user.settings'])
            ->get()
            ->filter(function ($task) {
                return $task->user->settings?->email_notifications === true;
            });

        if ($expiredTasks->isEmpty()) {
            return 0;
        }

        $createdCount = 0;

        foreach ($expiredTasks as $task) {
            $existingReminder = TaskReminder::where('task_id', $task->id)
                ->whereNull('send_at')
                ->first();

            if (!$existingReminder) {
                TaskReminder::create([
                    'task_id' => $task->id,
                    'remind_at' => now(),
                    'send_at' => null,
                ]);
                $createdCount++;
            }
        }

        return $createdCount;
    }

    /**
     * Send pending notifications
     */
    protected function sendPendingNotifications(bool $isDryRun): array
    {
        $pendingReminders = TaskReminder::query()
            ->where('remind_at', '<=', now())
            ->whereNull('send_at')
            ->with(['task.user.settings'])
            ->get();

        $sentCount = 0;
        $failedCount = 0;

        foreach ($pendingReminders as $reminder) {
            try {
                $task = $reminder->task;
                $user = $task->user;
                $locale = $user->settings?->language ?? config('app.locale');

                if (!$isDryRun) {
                    Mail::to($user->email)
                        ->send(new TaskExpiredNotification($task, $locale));

                    $reminder->update(['send_at' => now()]);
                }

                $sentCount++;
                
                if ($this->output->isVerbose()) {
                    $this->line(" {$user->email} - {$task->title}");
                }
            } catch (Exception $e) {
                $failedCount++;
                $this->error(" Failed: Task ID {$reminder->task_id} - {$e->getMessage()}");
            }
        }

        return ['sent' => $sentCount, 'failed' => $failedCount];
    }

    /**
     * Display execution summary
     */
    protected function displaySummary(int $remindersCreated, array $notificationsSent): void
    {
        $this->newLine();
        $this->info('📊 Summary:');
        $this->line("  • Reminders created: {$remindersCreated}");
        $this->line("  • Notifications sent: {$notificationsSent['sent']}");
        
        if ($notificationsSent['failed'] > 0) {
            $this->line("  • Failed: {$notificationsSent['failed']}");
        }

        if ($remindersCreated === 0 && $notificationsSent['sent'] === 0) {
            $this->comment('  ℹ No tasks to process');
        }
    }
}
