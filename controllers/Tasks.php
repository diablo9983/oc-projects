<?php namespace BootstrapHunter\Projects\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Request;
use BootstrapHunter\Projects\Models\Projects as ProjectsModel;
use BootstrapHunter\Projects\Models\Tasks as TasksModel;

class Tasks extends Controller
{
    public $implement = [];

    public $params = [
      'id'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('BootstrapHunter.Projects', 'bh-projects', 'projects');

        $this->bodyClass = 'compact-container';
        $this->pageTitle = 'bootstraphunter.projects::lang.plugin.projects';
    }

    public function index($id = 0)
    {
      $this->vars['project'] = ProjectsModel::find($id);
      //$this->addCss('/plugins/bootstraphunter/projects/assets/css/projects.css', 'BootstrapHunter.Projects');
      //$this->addJs('/plugins/bootstraphunter/projects/assets/js/projects.js', 'BootstrapHunter.Projects');
    }
}
