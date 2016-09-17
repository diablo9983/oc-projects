<?php namespace BootstrapHunter\Projects\Models;

use Model;

class TaskGroups extends Model
{
  use \October\Rain\Database\Traits\Validation;
  
  public $hasMany = [
    'task' => [
      'BootstrapHunter\Projects\Models\Task',
      'order' => 'order asc'
    ]
  ];

  public $rules = [
    'name' => 'required'
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

  public static function addGroup($data)
  {
    $order = static::where('project',$data['project'])->orderBy('order','desc')->first();
    $order = is_null($order) ? 0 : $order->order + 1;

    $group          = new TaskGroups;
    $group->name    = $data['name'];
    $group->project = $data['project'];
    $group->order   = $order;
    $group->save();

    return true;
  }

}
