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

  public $casts = [
    'hidden' => 'boolean'
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

  public static function saveGroup($data)
  {
    if(isset($data['id'])) {
      $group = TaskGroups::find($data['id']);
    } else {
      $group = new TaskGroups;
    }
    if(isset($data['project'])) {
      $order = static::where('project',$data['project'])->orderBy('order','desc')->first();
      $order = is_null($order) ? 0 : $order->order + 1;

      $group->project = $data['project'];
      $group->order   = $order;
    }

    $group->name = $data['name'];

    $group->save();

    return $group;
  }

  public function scopeVisible($query)
  {
    return $query->where('hidden', 0);
  }

  public function scopeFromProject($query, $id)
  {
    return $query->where('project', $id);
  }

}
