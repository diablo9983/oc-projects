<?php namespace BootstrapHunter\Projects\Models;

use Model;

class Task extends Model
{
    protected $dates = ['created_at'];

    public $table = 'bootstraphunter_projects_tasks';

    public static function addTask($data)
    {
      $order = static::where('task_groups_id',$data['group_id'])->orderBy('order','desc')->first();
      $order = is_null($order) ? 0 : $order->order + 1;

      $task                 = new Task;
      $task->name           = $data['name'];
      $task->description    = $data['description'];
      $task->task_groups_id = $data['group_id'];
      $task->order          = $order;
      $task->save();

      $return       = new \stdClass();
      $return->name = $task->name;
      $return->id   = $task->id;

      return ['group_id' => $data['group_id'], 'task' => $return];
    }
}
