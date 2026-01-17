<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            // Delete key and value columns
            $table->dropColumn(['key', 'value']);
            
            // Add specific configuration fields
            $table->string('theme')->default('light'); // light, dark
            $table->string('language')->default('es'); // es, en
            $table->boolean('email_notifications')->default(true);
            $table->string('timezone')->default('UTC');
            $table->string('date_format')->default('d/m/Y'); // d/m/Y, Y-m-d, m/d/Y
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table) {
            // Restore key and value columns
            $table->string('key');
            $table->string('value');
            
            // Delete specific fields
            $table->dropColumn([
                'theme',
                'language',
                'email_notifications',
                'timezone',
                'date_format'
            ]);
        });
    }
};
