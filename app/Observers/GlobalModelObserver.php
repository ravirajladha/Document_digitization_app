<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GlobalModelObserver
{

 

    public function created(Model $model)
    {
        //this checks the data is not getting added through seeder , as the system asks for the user_id and while seeding there is no user_id
       
    

        $userId = Auth::id() ?? 1;
        if ($userId) {
            DB::table('log_changes')->insert([
                'user_id' => $userId,
                'model_id' => $model->id,
                'model_type' => get_class($model),
                'action' => 'create',
                'changes' => json_encode($model->getAttributes()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function updated(Model $model)
    {
        if (app()->runningInConsole()) {
            // Do not log if running in console (e.g., migrations, seedings)
            return;
        }
    
        $userId = Auth::id();
        if ($userId) {
            DB::table('log_changes')->insert([
                'user_id' => $userId,
                'model_id' => $model->id,
                'model_type' => get_class($model),
                'action' => 'update',
                'changes' => json_encode($model->getChanges()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function deleted(Model $model)
    {
        $userId = Auth::id();
        if ($userId) {
            DB::table('log_changes')->insert([
                'user_id' => $userId,
                'model_id' => $model->id,
                'model_type' => get_class($model),
                'action' => 'delete',
                'changes' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
