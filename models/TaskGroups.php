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

  public $belongsTo = [
    'project' => 'BootstrapHunter\Projects\Models\Projects'
  ];

  public $casts = [
    'hidden' => 'boolean'
  ];

  protected $fillable = ['name'];

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

  public function scopeVisible($query)
  {
    return $query->where('hidden', 0);
  }

  public function scopeFromProject($query, $id)
  {
    return $query->where('project_id', $id);
  }

}
