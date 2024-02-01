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
        //the below function checks and notifiy the user if any compliances are under 30 days
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
    
//the below function created new compliances, if the status is settled.

    $schedule->call(function () {
        $today = Carbon::today();
        // Select compliances with status 1 and due date less than or equal to today
        $compliances = Compliance::where('due_date', '<=', $today)
                                 ->where('is_recurring', 1)
                                 ->where('status', 1)
                                 ->get();
    
        foreach ($compliances as $compliance) {
            // Only create a new compliance if the current one is due, is marked as recurring, and has a status of 1
             if ($compliance->due_date <= $today && $compliance->is_recurring && $compliance->status == 1) {
                // If the due date has passed, replicate and create a new compliance for the next period
                $newCompliance = $compliance->replicate(['id']); // Exclude id when replicating
                $newCompliance->due_date = $today->addYear(); // Set the new due date from today
                $newCompliance->status = 0; // Set to pending or your default status
                $newCompliance->save();
    
                // Update the current compliance to indicate it's been processed for recurrence
          
                $compliance->status = 3; // Assuming 3 indicates processed status, which should not be repeated in the future
                $compliance->save();
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
