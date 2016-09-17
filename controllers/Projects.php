<?php namespace BootstrapHunter\Projects\Controllers;

use Request;
use BackendMenu;
use Backend\Classes\Controller;
use BootstrapHunter\Projects\Models\Projects as ProjectsModel;
use BootstrapHunter\Projects\Models\Task as TaskModel;
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

    public function onOpenAddProjectForm()
    {
      $data = [];
      if(Request::input('id')) {
        $data['id'] = Request::input('id');
        $data['project'] = ProjectsModel::find(Request::input('id'));
      }
      return $this->makePartial('addProjectForm', $data);
    }

    public function onOpenAddTaskForm()
    {
      $data['group_id'] = Request::input('group_id');
      if(Request::input('id')) {
        $data['id'] = Request::input('id');
        $data['task'] = TaskModel::find(Request::input('id'));
      }
      return $this->makePartial('addTaskForm', $data);
    }

    public function onOpenOrderGroupsPopup()
    {
      $id = Request::input('project_id');
      $data['groups'] = TaskGroupsModel::where('project', $id)->orderBy('order','asc')->get();
      return $this->makePartial('orderGroupsPopup', $data);
    }

    public function onOpenAddGroupForm()
    {
      $data['project_id'] = Request::input('project_id');
      if(Request::input('id')) {
        $data['id'] = Request::input('id');
        $data['group'] = TaskGroupsModel::find(Request::input('id'));
      }
      return $this->makePartial('addGroupForm', $data);
    }

    public function onAddGroup()
    {
      $data['name'] = Request::input('name');
      $data['project'] = Request::input('project_id');
      TaskGroupsModel::addGroup($data);

      return true;
    }

    public function onOrderGroups()
    {
      $groups = Request::input('groups');
      TaskGroupsModel::updateOrder($groups);
    }

    public function onGetGroups()
    {
      $id = Request::input('id');
      $this->vars['project'] = ProjectsModel::find($id);
      $this->vars['groups'] = TaskGroupsModel::where('project', $id)->orderBy('order','ASC')->get();
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

    public function onAddTask()
    {
      $data['name'] = Request::input('name');
      $data['description'] = Request::input('description');
      $data['group_id'] = Request::input('group_id');
      $task = TaskModel::addTask($data);

      return ['group' => $task['group_id'], 'task' => $this->makePartial('task', ['task' => $task['task']])];
    }

    public function onTasksSave()
    {
      $group_id = (int) Request::input('group');
      $tasks = (array) Request::input('tasks');

      $i = 0;
      foreach($tasks as $task) {
        TaskModel::where('id',$task)->update(['task_groups_id' => $group_id, 'order' => $i]);
        $i++;
      }
    }

    public function onGetProjectList()
    {
      $this->vars['projects'] = ProjectsModel::all();
    }
}
