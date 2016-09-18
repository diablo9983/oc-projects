<?php namespace BootstrapHunter\Projects\Models;

use Model;

class Projects extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [
      'name'        => 'required'
    ];

    protected $dates = ['created_at'];

    public $table = 'bootstraphunter_projects_projects';

    public static function addProject($data)
    {
      $project = new Projects;
      $project->name = $data['name'];
      $project->description = $data['description'];
      $project->save();

      return $project;
    }

    public static function editProject($id, $data)
    {
      $project = Projects::find($id);
      $project->name = $data['name'];
      $project->description = $data['description'];
      $project->save();

      return true;
    }

    public static function deleteProject($id)
    {
      Projects::destroy($id);

      return true;
    }
}
