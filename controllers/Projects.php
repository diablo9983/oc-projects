<?php namespace BootstrapHunter\Projects\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Request;
use BootstrapHunter\Projects\Models\Projects as ProjectsModel;
//use BootstrapHunter\Projects\Models\Tickets as TicketsModel;

class Projects extends Controller
{
    public $implement = [];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('BootstrapHunter.Projects', 'bh-projects', 'projects');

        $this->bodyClass = 'compact-container';
        $this->pageTitle = 'bootstraphunter.projects::lang.plugin.projects';
    }

    public function index()
    {
      $this->addCss('/plugins/bootstraphunter/projects/assets/css/projects.css', 'BootstrapHunter.Projects');
      $this->addJs('/plugins/bootstraphunter/projects/assets/js/projects.js', 'BootstrapHunter.Projects');
    }

    public function onAddProjectForm()
    {
      $data = [];
      if(Request::input('id')) {
        $data['id'] = Request::input('id');
        $data['project'] = ProjectsModel::find(Request::input('id'));
      }
      return $this->makePartial('addProject', $data);
    }

    public function onAddProject()
    {
      $data['name'] = Request::input('name');
      $data['description'] = Request::input('description');
      ProjectsModel::addProject($data);

      return true;
    }

    public function onEditProject()
    {
      $id = Request::input('id');
      $data['name'] = Request::input('name');
      $data['description'] = Request::input('description');
      ProjectsModel::editProject($id,$data);

      return true;
    }

    public function onDeleteProject()
    {
      $id = Request::input('id');
      ProjectsModel::deleteProject($id);

      return true;
    }

    public function onGetProjectList()
    {
      $this->vars['projects'] = ProjectsModel::all();
    }
}
