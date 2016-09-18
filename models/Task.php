<?php namespace BootstrapHunter\Projects\Models;

use Model;

class Task extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = ['created_at'];

    public $rules = [
      'name'        => 'required'
    ];

    public $table = 'bootstraphunter_projects_tasks';

    public static function saveTask($data)
    {
      if(isset($data['id'])) {
        $task = Task::find($data['id']);
      } else {
        $task = new Task;
      }
      if(isset($data['group_id'])) {
        $order = static::where('task_groups_id',$data['group_id'])->orderBy('order','desc')->first();
        $order = is_null($order) ? 0 : $order->order + 1;

        $task->order = $order;
        $task->task_groups_id = $data['group_id'];
      }

      $task->name           = $data['name'];
      $task->description    = $data['description'];

      $task->save();

      return $task;
    }
}
