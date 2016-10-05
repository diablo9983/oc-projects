<?php namespace BootstrapHunter\Projects\Models;

use Model;

class Task extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = ['created_at','updated_at', 'due_date'];

    public $rules = [
      'name'        => 'required'
    ];

    public $hasOne = [
      'user' => [
        'Backend\Models\User',
        'key' => 'id',
        'otherKey' => 'user_id'
      ]
    ];

    public $table = 'bootstraphunter_projects_tasks';

}
