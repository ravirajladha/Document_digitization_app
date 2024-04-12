<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSetting extends Model
{
    // If you want to specify the table associated with this model
    protected $table = 'project_settings';

    // If you want to specify which attributes can be mass assignable
    protected $fillable = ['project_name', 'logo', 'favicon'];

}
//create reviewer status page
