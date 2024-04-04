<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Alert};

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Default type based on permission

    public function showNotifications(Request $request)
    {
        Alert::query()->update(['is_read' => 1]);
        
        $user = Auth::user();
        $notificationsQuery = Alert::with('masterDocData'); // Eager loading the relation
    
        // Determine which types of notifications the user is allowed to view
        $canViewCompliance = $user->hasPermission('View Compliance Notifications');
        $canViewRecipient = $user->hasPermission('View Recipient Notifications');
    
        // Determine the default type based on permission
        $defaultType = null;
        if ($canViewCompliance) {
            $defaultType = 'compliance';
        } elseif ($canViewRecipient) {
            $defaultType = 'document_assignment';
        }
    
        // Get the requested type, or the default if none is requested
        $type = $request->query('type', $defaultType);
    
        // Apply the filtering based on the type and permission
        if ($type === 'compliance' && $canViewCompliance) {
            $notificationsQuery->whereNotNull('compliance_id');
        } elseif ($type === 'document_assignment' && $canViewRecipient) {
            $notificationsQuery->whereNotNull('document_assignment_id');
        } else {
            // If the user has neither permission or the type is not recognized, don't load any notifications
            $notificationsQuery->whereRaw('1 = 0'); // This will return an empty collection
        }
    
        $notifications = $notificationsQuery->orderBy('created_at', 'desc')->get();
    
        // Process the notifications as needed (e.g., attaching dynamic_id)
        // ...
    
        return view('pages.notifications', [
            'notifications' => $notifications,
            // Pass additional data as needed
        ]);
    }
    
}
