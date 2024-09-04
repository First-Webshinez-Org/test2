<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $env = config('app.env');
        $email = config('mail.username');

        // Schedule the backup (only db) command to run every hour
        $schedule->command('backup:run --only-db')
            ->hourly()
            ->between('9:00', '01:00') // Run between 9:00 AM and 1:00 AM the next day
            ->onFailure(function () {
                \Log::error('Database backup failed');
            })
            ->onSuccess(function () {
                \Log::info('Database backup completed successfully');
            });

        // Schedule the backup command to run at 12:00 pm and 6:00 pm
        $schedule->command('backup:run --only-files')
            ->dailyAt('12:00') // Run daily at 12:00 PM
            ->onFailure(function () {
                \Log::error('Files backup failed');
            })
            ->onSuccess(function () {
                \Log::info('Files backup completed successfully');
            });

        $schedule->command('backup:run --only-files')
            ->dailyAt('18:00') // Run daily at 6:00 PM
            ->onFailure(function () {
                \Log::error('Files backup failed');
            })
            ->onSuccess(function () {
                \Log::info('Files backup completed successfully');
            });

        // Schedule the cleanup command to run daily at 4:00 am
        $schedule->command('backup:clean')
            ->weeklyOn(0, '04:00')  // 0 represents Sunday
            ->onFailure(function () {
                \Log::error('Cleanup failed');
            })
            ->onSuccess(function () {
                \Log::info('Cleanup completed successfully');
            });

        if ($env === 'live') {
            //Scheduling backup, specify the time when the backup will get cleaned & time when it will run.

            //Schedule to create recurring invoices
            $schedule->command('pos:generateSubscriptionInvoices')->dailyAt('23:30');
            $schedule->command('pos:updateRewardPoints')->dailyAt('23:45');

            $schedule->command('pos:autoSendPaymentReminder')->dailyAt('8:00');
        }

        if ($env === 'demo') {
            //IMPORTANT NOTE: This command will delete all business details and create dummy business, run only in demo server.
            $schedule->command('pos:dummyBusiness')
                    ->cron('0 */3 * * *')
                    //->everyThirtyMinutes()
                    ->emailOutputTo($email);
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
