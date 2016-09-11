<?php namespace BootstrapHunter\Projects\Controllers;

use Request;
use BackendMenu;
use Backend\Classes\Controller;
use BootstrapHunter\Projects\Models\Projects as ProjectsModel;
use BootstrapHunter\Projects\Models\Tasks as TasksModel;
use BootstrapHunter\Projects\Models\TaskGroups as TaskGroupsModel;

class Projects extends Controller
{
    public $implement = [];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('BootstrapHunter.Projects', 'bh-projects', 'projects');

        $this->bodyClass = 'compact-container';
        $this->pageTitle = 'bootstraphunter.projects::lang.plugin.projects';

        $this->addCss('/plugins/bootstraphunter/projects/assets/css/projects.css', 'BootstrapHunter.Projects');
        $this->addJs('/plugins/bootstraphunter/projects/assets/js/projects.js', 'BootstrapHunter.Projects');
    }

    public function index()
    {
      $this->vars['projects'] = $this->makePartial('projects', ['projects' => ProjectsModel::all()]);
    }

    public function view($id = 0)
    {
      $this->addJs('/plugins/bootstraphunter/projects/assets/js/sortable.js', 'BootstrapHunter.Projects');
      $this->addJs('/plugins/bootstraphunter/projects/assets/js/jquery.fn.sortable.js', 'BootstrapHunter.Projects');
      $this->addJs('/plugins/bootstraphunter/projects/assets/js/tasks.js', 'BootstrapHunter.Projects');

      $this->vars['project'] = ProjectsModel::find($id);
      $this->vars['groups'] = TaskGroupsModel::where('project','=',$id)->orderBy('order','ASC')->get();
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
