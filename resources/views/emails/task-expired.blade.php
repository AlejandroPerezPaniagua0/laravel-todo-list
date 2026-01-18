<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.task_expired_subject', ['title' => $task->title]) }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .intro-text {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .task-card {
            background-color: #fff5f5;
            border-left: 4px solid #ef4444;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .task-field {
            margin-bottom: 15px;
        }
        .task-field-label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .task-field-value {
            color: #6b7280;
            font-size: 15px;
        }
        .task-title-value {
            font-size: 18px;
            color: #1f2937;
            font-weight: 600;
        }
        .expired-badge {
            display: inline-block;
            background-color: #fee2e2;
            color: #dc2626;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #4f46e5;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #4338ca;
        }
        .footer-message {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 4px;
            color: #6b7280;
            font-size: 14px;
            margin-top: 30px;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            color: #9ca3af;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            margin: 5px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 20px 15px;
            }
            .email-header {
                padding: 30px 15px;
            }
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f7; padding: 40px 0;">
        <tr>
            <td align="center">
                <div class="email-wrapper">
                    <!-- Header -->
                    <div class="email-header">
                        <h1>📋 {{ __('app.app_name') }}</h1>
                    </div>

                    <!-- Body -->
                    <div class="email-body">
                        <p class="greeting">{{ __('emails.task_expired_greeting', ['name' => $task->user->name]) }}</p>
                        
                        <p class="intro-text">{{ __('emails.task_expired_intro') }}</p>

                        <!-- Task Card -->
                        <div class="task-card">
                            <div class="task-field">
                                <div class="task-field-label">{{ __('emails.task_title') }}</div>
                                <div class="task-title-value">{{ $task->title }}</div>
                            </div>

                            @if($task->content)
                                <div class="task-field">
                                    <div class="task-field-label">{{ __('emails.task_description') }}</div>
                                    <div class="task-field-value">{{ $task->content }}</div>
                                </div>
                            @endif

                            <div class="task-field">
                                <div class="task-field-label">{{ __('emails.task_due_date') }}</div>
                                <div class="task-field-value">
                                    <span class="expired-badge">
                                        {{ __('emails.task_expired_on') }} {{ $task->due_date->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Call to Action -->
                        <div class="button-container">
                            <a href="{{ route('tasks.index') }}" class="button">
                                {{ __('emails.view_task') }}
                            </a>
                        </div>

                        <!-- Footer Message -->
                        <div class="footer-message">
                            <p>💡 {{ __('emails.footer_message') }}</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="email-footer">
                        <p>{{ __('emails.thanks') }}</p>
                        <p>{{ __('emails.regards') }},</p>
                        <p><strong>{{ __('emails.app_name') }}</strong></p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
