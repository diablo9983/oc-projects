<?php namespace BootstrapHunter\Projects\Models;

use Model;

class Task extends Model
{
    protected $dates = ['created_at'];

    public $table = 'bootstraphunter_projects_tasks';

}
