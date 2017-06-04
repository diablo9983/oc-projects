<?php namespace BootstrapHunter\Projects\Models;

use Model;

class Projects extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [
      'name' => 'required'
    ];

    protected $dates = ['created_at','updated_at'];

    public $table = 'bootstraphunter_projects_projects';

    public $belongsToMany = [
        'user' => [
            'Backend\Models\User',
            'table' => 'bootstraphunter_projects_project_user',
            'key'   => 'project_id'
        ]
    ];

    public $hasMany = [
        'groups' => [
            'BootstrapHunter\Projects\Models\TaskGroups',
            'key'       => 'project_id',
            'otherKey'  => 'id'
        ]
    ];

    public $hasManyThrough = [
        'task' => [
            'BootstrapHunter\Projects\Models\Task',
            'key'       => 'project_id',
            'through'   => 'BootstrapHunter\Projects\Models\TaskGroups'
        ]
    ];
}
