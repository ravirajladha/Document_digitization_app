<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\State;
use App\Models\Set;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class PermissionSeeder extends Seeder
{
    public function run()
    {

        // $crudActions = [
        //     'create' => '1',
        //     'read' => '2',
        //     'update' => '3',
        //     'delete' => '4',
        // ];


        $permissionsWithCRUD = [
            // ['name' => 'dashboard', 'display_name' => 'Dashboard ', 'action' => 2],
            ['name' => 'profile.edit', 'display_name' => 'View Profile', 'action' => 1],
            ['name' => 'profile.update', 'display_name' => 'Update Profile ', 'action' => 3],
            ['name' => 'sets.view', 'display_name' => 'View Sets ', 'action' => 2],
            ['name' => 'sets.viewDocuments', 'display_name' => 'View Documents from Sets ', 'action' => 2],
            ['name' => 'sets.viewUpdated', 'display_name' => 'View Sets ', 'action' => 2],
            ['name' => 'sets.add', 'display_name' => 'Add Sets ', 'action' => 1],
            ['name' => 'sets.update', 'display_name' => 'Update Sets ', 'action' => 3],
            //recievers type
            ['name' => 'receiverTypes.view', 'display_name' => 'View Receiver Type ', 'action' => 2],
            //ajax response to get the receiver types
            // ['name' => 'receiverTypes.updated', 'display_name' => 'Update Receiver Type ', 'action' => 2],
            ['name' => 'receiverTypes.add', 'display_name' => 'Create Receiver Type', 'action' => 1],
            ['name' => 'receiverTypes.update', 'display_name' => 'Update Receiver Type ', 'action' => 3],
            //receivers
            ['name' => 'receivers.index', 'display_name' => 'View Receivers ', 'action' => 2],
            ['name' => 'receivers.store', 'display_name' => 'Add Receivers ', 'action' => 1],
            ['name' => 'receivers.update', 'display_name' => 'Update Receivers ', 'action' => 3],
            //get receivers by type
            ['name' => 'receivers.byType', 'display_name' => 'View Receivers By Type ', 'action' => 2],
            ['name' => 'receivers.updated', 'display_name' => 'View Updated Receivers ', 'action' => 2],
            //document assignment
            ['name' => 'documents.assigned.show', 'display_name' => 'View Assigned Documents ', 'action' => 2],
            ['name' => 'user.documents.assigned.show', 'display_name' => 'View Assigned Documents By User ', 'action' => 2],
            ['name' => 'documents.assigned.toggleStatus', 'display_name' => 'Update Document Assignment Status ', 'action' => 3],
            ['name' => 'documents.assign.toReceiver', 'display_name' => 'Assign Document (Add) ', 'action' => 1],
            //document types
            ['name' => 'document_types.index', 'display_name' => 'View Document Types ', 'action' => 2],
            ['name' => 'document_types.store', 'display_name' => 'Add Document Types ', 'action' => 1],
            ['name' => 'documents.view.first', 'display_name' => 'View Documents By Doc Type ', 'action' => 2],
            ['name' => 'documents.types.all', 'display_name' => 'View All Document Types ', 'action' => 2],
            //dynamic columns
            ['name' => 'fields.create_first_step', 'display_name' => 'View Document Fields ', 'action' => 2],
            ['name' => 'document_fields.store', 'display_name' => 'Add Document Fields ', 'action' => 1],
         
            ['name' => 'document_fields.edit', 'display_name' => 'Update Document Fields  ', 'action' => 3],
            ['name' => 'documents.getByType', 'display_name' => 'View Document Fields by Document Type ', 'action' => 2],
            ['name' => 'documents.store', 'display_name' => 'Add Other Document Details ', 'action' => 1],
            // ['name' => 'master_documents.addBasicDetail', 'display_name' => 'Add Basic Details Document Form ', 'action' => 2],
            // ['name' => 'documents.create_first_step', 'display_name' => 'View Add Document Form ', 'action' => 2],


            ['name' => 'documents.review', 'display_name' => 'Main Document View ', 'action' => 2],
            ['name' => 'documents.data.add', 'display_name' => 'Add Basic Details Document Form ', 'action' => 1],
            ['name' => 'documents.data.first.update', 'display_name' => 'Update Basic Details Document Form ', 'action' => 3],
            ['name' => 'documents.creation.continue', 'display_name' => 'Add Advance Details Document Form ', 'action' => 1],
          
            // ['name' => 'documents.view.first.submit', 'display_name' => 'Access ', 'action' => 2],
            // ['name' => 'documents.view', 'display_name' => 'Access ', 'action' => 2],
            // ['name' => 'documents.edit', 'display_name' => 'Access ', 'action' => 2],
            ['name' => 'documents.basic_detail.edit', 'display_name' => 'Update Basic Document Detail ', 'action' => 3],
            ['name' => 'documents.updateStatus', 'display_name' => 'Update Document Status ', 'action' =>3],
            ['name' => 'documents.filter', 'display_name' => 'Filter Document ', 'action' => 2],
          
            ['name' => 'configure', 'display_name' => 'Configure ', 'action' => 2],
            ['name' => 'master_data.bulk_upload', 'display_name' => 'View Bulk Upload  ', 'action' => 2],
            ['name' => 'master_documents.bulk_upload', 'display_name' => 'Add Master Bulk Upload ', 'action' => 1],
            ['name' => 'child_documents.bulk_upload', 'display_name' => 'Add Child Bulk Upload ', 'action' => 1],
            ['name' => 'compliances.index', 'display_name' => 'View Compliances ', 'action' => 2],
            ['name' => 'compliances.store', 'display_name' => 'Add Compliances ', 'action' => 1],
            ['name' => 'compliances.status_change', 'display_name' => 'Update Compliances Status ', 'action' => 3],
            ['name' => 'notifications.index', 'display_name' => 'View Notifications ', 'action' => 2],
            ['name' => 'users.index', 'display_name' => 'View Users ', 'action' => 2],
            ['name' => 'users.store', 'display_name' => 'Add Users ', 'action' => 1],
           
    
           

            // ... add other permissions as needed
        ];


      

        foreach ($permissionsWithCRUD as $permissionData) {
            Permission::insert([
                'name' => $permissionData['name'],
                'display_name' => $permissionData['display_name'],
                'action' => $permissionData['action'],
                // You might want to include a timestamp for created_at and updated_at
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}