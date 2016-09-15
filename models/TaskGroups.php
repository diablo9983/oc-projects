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

  public static function updateOrder($groups)
  {
    for($i = 0; $i < count($groups); $i++) {
      static::where('id',$groups[$i])->update(['order' => $i]);
    }
    return true;
  }

}
