<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Receiver, Notification};

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class NotificationController extends Controller
{

    public function showNotifications1()
    {
        // dd("test");
        Notification::query()->update(['is_read' => 1]);
        $notifications = Notification::with(['compliance'])->orderBy('created_at', 'desc')
            ->get();
        // dd($notificatons);
        return view('pages.notifications', [
            'notifications' => $notifications,


        ]);
    }

    public function showNotifications(Request $request)
    {
        Notification::query()->update(['is_read' => 1]);
    
        $type = $request->query('type');
        $notificationsQuery = Notification::with('masterDocData'); // Eager loading the relation
    
        if ($type == 'compliance') {
            $notificationsQuery->whereNotNull('compliance_id');
        } elseif ($type == 'document_assignment') {
            $notificationsQuery->whereNotNull('document_assignment_id');
        }
    
        $notifications = $notificationsQuery->orderBy('created_at', 'desc')->get();
    

        foreach ($notifications as $notification) {
            $tableName = $notification->masterDocData->document_type_name;
    
            // Check if the table exists
            if (Schema::hasTable($tableName)) {
                // Assuming 'doc_id' is a column in your dynamic table
                $relatedRecord = DB::table($tableName)->where('doc_id', $notification->doc_id)->first();
    
                if ($relatedRecord) {
                    // Attach the id from the dynamic table to the notification
                    $notification->dynamic_id = $relatedRecord->id;
                }
            }
        }


        // Uncomment this to debug
        // dd($notifications);
    
        return view('pages.notifications', [
            'notifications' => $notifications,
        ]);
    }
    
}
