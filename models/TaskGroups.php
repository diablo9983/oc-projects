<?php namespace BootstrapHunter\Projects\Models;

use Model;

class TaskGroups extends Model
{
  public $hasMany = [
    'task' => [
      'BootstrapHunter\Projects\Models\Task',
      'order' => 'order asc'
    ]
  ];

  protected $dates = ['created_at', 'updated_at'];

  public $table = 'bootstraphunter_projects_task_groups';

}
