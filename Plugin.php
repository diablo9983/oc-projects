<?php namespace BootstrapHunter\Projects;

use Backend\Models\User;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
      User::extend(function($model) {
        $model->belongsToMany['project'] = [
          'BootstrapHunter\Projects\Models\Projects',
          'table' => 'bootstraphunter_projects_project_user',
          'otherKey' => 'project_id'
        ];
      });
    }
}
