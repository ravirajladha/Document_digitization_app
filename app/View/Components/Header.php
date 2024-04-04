<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use App\Models\Master_doc_type;
use App\Models\Alert;
use Illuminate\Support\Facades\Auth;

class Header extends Component
{
    /**
     * The document types.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $doc_types;
    public $notifications;
    public $notificationsCount;
    public $pageName;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->doc_types = Master_doc_type::all();

        // Assuming 'View Compliance Notifications' and 'View Document Assignment Notifications' are your permission names
        $user = Auth::user();
        $notificationsQuery = Alert::latest();

        if ($user->hasPermission('View Compliance Notifications') && $user->hasPermission('View Recipient Notifications')) {
            // User has both permissions; no additional filtering is needed
        } elseif ($user->hasPermission('View Compliance Notifications')) {
            $notificationsQuery->whereNotNull('compliance_id');
        } elseif ($user->hasPermission('View Recipient Notifications')) {
            $notificationsQuery->whereNotNull('document_assignment_id');
        } else {
            // User has neither permission; you might want to handle this scenario
            $notificationsQuery = $notificationsQuery->where('id', 0); // No notifications
        }

        $this->notifications = $notificationsQuery->take(5)->get();
        $this->notificationsCount = $notificationsQuery->where('is_read', 0)->count();

        //header name
        $routeName = Route::currentRouteName();

        // Map route names to page names
        switch ($routeName) {
            case 'dashboard':
                $this->pageName = 'Dashboard';
                break;
            case 'profile.edit':
                $this->pageName = 'Profile';
                break;
            case 'error-403':
                $this->pageName = 'Try again later';
                break;
            case 'sets.view':
                $this->pageName = 'Sets';
                break;
            case 'receiverTypes.view':
                $this->pageName = 'Receiver Type';
                break;
            case 'showReceivers':
                $this->pageName = 'Receivers';
                break;
            case 'receiverTypes':
                $this->pageName = 'Receiver';
                break;
            case 'documents.assigned.show':
                $this->pageName = 'Assign Documents';
                break;
            case 'user.documents.assigned.show':
                $this->pageName = 'Assigned Documents';
                break;
            case 'document_types.index':
                $this->pageName = 'Document Type';
                break;
            case 'document_fields.view':
                $this->pageName = 'Document Field';
                break;
            case 'documents.add_document_first':
                $this->pageName = 'Add Document Basic Details';
                break;
            case 'documents.review':
                $this->pageName = 'Document';
                break;
            case 'documents.creation.continue':
                $this->pageName = 'Document Miscellaneous Data';
                break;
            case 'edit_document_basic_detail':
                $this->pageName = 'Update Document Details';
                break;
            case 'configure':
                $this->pageName = 'Data Sets';
                break;
            case 'master_data.bulk_upload':
                $this->pageName = 'Bulk Upload Documents';
                break;
            case 'compliances.index':
                $this->pageName = 'Compliances';
                break;
            case 'notifications.index':
                $this->pageName = 'Notifications';
                break;
            case 'users.index':
                $this->pageName = 'Users';
                break;
            case 'soldLand.view':
                $this->pageName = 'Sold Lands';
                break;
            case 'soldLand.add':
                $this->pageName = 'Add Sold Land';
                break;
            case 'soldLand.edit':
                $this->pageName = 'Edit Sold Land';
                break;
            case 'soldLand.show':
                $this->pageName = 'View Sold Land';
                break;
            case 'logs.action-logs':
                $this->pageName = 'Action Logs';
                break;
            case 'logs.http-request-logs':
                $this->pageName = 'HTTP Request Logs';
                break;

                // Add more cases for other routes...
            default:
                $this->pageName = ''; // Default page name
                break;
        }
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
