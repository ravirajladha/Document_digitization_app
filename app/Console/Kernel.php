<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\{Compliance};
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        //the below function checks and notificy the user if any compliances are under 30 days
        $schedule->call(function () {
         
            $notificationService = resolve('App\Services\NotificationService');
            $systemUserId = 1; 
            // Logic to check compliances due in the next 30 days
            $upcomingCompliances = Compliance::where('due_date', '<=', now()->addDays(30))
                                              ->where('status', 0) // Assuming 'status' field exists
                                              ->get();
                                            //   dd("213232");
               // Create notifications for each upcoming compliance
            //    dd($upcomingCompliances);
        foreach ($upcomingCompliances as $compliance) {
            $notificationService->createComplianceNotification('upcoming', $compliance, $systemUserId);
        }
    })->daily();
    // })->daily();
//the below function created new compliances, if its recurring on the last day of the due_date

    $schedule->call(function () {
        $today = Carbon::today();
        $compliances = Compliance::where('due_date', '<=', $today)
                                 ->where('is_recurring', 1)
                                 ->get();

        foreach ($compliances as $compliance) {
            // Only create a new compliance if the current one is due and is marked as recurring
            if ($compliance->due_date->isToday() && $compliance->is_recurring) {
                $newCompliance = $compliance->replicate(['id']); // Exclude id when replicating
                $newCompliance->due_date = $compliance->due_date->addYear();
                $newCompliance->status = 0; // Set to pending or your default status
                $newCompliance->save();

                // Optionally, you can call a method to create a notification about this new compliance
                // $notificationService->createComplianceNotification('renewed', $newCompliance, $systemUserId);
            }
        }
    })->daily();



    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
