<?php namespace BootstrapHunter\Projects\Controllers;

use Flash;
use Request;
use Response;
use BackendMenu;
use Backend\Models\User;
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

        $this->addCss('/plugins/bootstraphunter/projects/assets/css/selectize.css', 'BootstrapHunter.Projects');
        $this->addCss('/plugins/bootstraphunter/projects/assets/css/projects.css', 'BootstrapHunter.Projects');
        $this->addJs('/plugins/bootstraphunter/projects/assets/js/selectize.js', 'BootstrapHunter.Projects');
        $this->addJs('/plugins/bootstraphunter/projects/assets/js/projects.js', 'BootstrapHunter.Projects');
    }

    public function index()
    {
      $this->vars['projects'] = $this->makePartial('projects', ['projects' => ProjectsModel::all()]);
    }

    public function view($id = 0)
    {
      //$this->addCss('/plugins/bootstraphunter/projects/assets/css/selectize.css', 'BootstrapHunter.Projects');
      $this->addJs('/plugins/bootstraphunter/projects/assets/js/sortable.js', 'BootstrapHunter.Projects');
      $this->addJs('/plugins/bootstraphunter/projects/assets/js/jquery.fn.sortable.js', 'BootstrapHunter.Projects');
      //$this->addJs('/plugins/bootstraphunter/projects/assets/js/selectize.js', 'BootstrapHunter.Projects');
      $this->addJs('/plugins/bootstraphunter/projects/assets/js/tasks.js', 'BootstrapHunter.Projects');

      $this->vars['project'] = ProjectsModel::find($id);
      $this->vars['groups'] = ProjectsModel::find($id)->groups()->visible()->orderBy('order','ASC')->get();
    }

    public function onOpenAddProjectForm()
    {
      $data = [];
      if(Request::input('id')) {
        $data['id'] = Request::input('id');
        $data['project'] = ProjectsModel::find(Request::input('id'));
        $data['users'] = ProjectsModel::find(Request::input('id'))->user;
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

    public function onOpenManageGroupsPopup()
    {
      $id = Request::input('project_id');
      $data['groups'] = TaskGroupsModel::fromProject($id)->orderBy('order','asc')->get();
      return $this->makePartial('manageGroupsPopup', $data);
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

    public function onSaveGroup()
    {
      $id = Request::input('id', 0);
      $project = Request::input('project_id', 0);

      if($id) {
        $group = TaskGroupsModel::find($id);
      } else {
        $group = new TaskGroupsModel;
      }

      $group->name = Request::input('name');
      $group->color = Request::input('color');
      $group->icon = Request::input('icon');

      if($project) {
        $order = TaskGroupsModel::fromProject($project)->orderBy('order','desc')->first();
        $order = is_null($order) ? 0 : $order->order + 1;

        $group->project = $project;
        $group->order   = $order;
      }

      $group->save();

      if($id) {
        Flash::success('Group has been updated.');
      } else {
        Flash::success('Group has been added.');
      }
      return true;
    }

    public function onDeleteGroup()
    {
      $e = 'lol';
    }

    public function onHideGroup()
    {
      $id = Request::input('id');
      $hide = !is_null(Request::input('hide')) ? intval(Request::input('hide')) : 1;

      $group = TaskGroupsModel::find($id);
      $group->hidden = $hide;
      $group->save();

      if($hide == 1) {
        Flash::success('Group has been hidden.');
      } else {
        Flash::success('Group has been shown.');
      }
      return ['hidden' => $hide, 'id' => $group->id];
    }

    public function onOrderGroups()
    {
      $groups = Request::input('groups');
      TaskGroupsModel::updateOrder($groups);

      Flash::success('Order has been updated.');

      return true;
    }

    public function onGetGroups()
    {
      $id = Request::input('id');
      $this->vars['project'] = ProjectsModel::find($id);
      $this->vars['groups'] = TaskGroupsModel::visible()->fromProject($id)->orderBy('order','ASC')->get();
    }

    public function onAddProject()
    {
      $id = Request::input('id' ,0);

      if($id) {
        $project = ProjectsModel::find($id);
      } else {
        $project = new ProjectsModel;
      }
      $project->name = Request::input('name');
      $project->description = Request::input('description');
      $project->save();

      if(!$id) {
        $task = new TaskGroupsModel(['name' => 'Backlog']);
        $project->groups()->save($task);
      }

      if($id) {
        $project->user()->detach();
      }

      if(Request::input('team') != '') {
        $team = explode(',',Request::input('team'));
        foreach($team as $user_id) {
          User::find($user_id)->project()->save($project);
        }
      }

      Flash::success('Project has been '.($id ? 'updated' : 'created').'.');

      return true;
    }

    public function onDeleteProject()
    {
      ProjectsModel::destroy(Request::input('id'));

      Flash::success('Project has been deleted.');

      return true;
    }

    public function onSaveTask()
    {
      if(Request::input('id')) $data['id'] = Request::input('id');
      $data['name'] = Request::input('name');
      $data['description'] = Request::input('description');
      if(Request::input('group_id')) $data['group_id'] = Request::input('group_id');
      $task = TaskModel::saveTask($data);

      if(Request::input('id')) {
        Flash::success('Task has been updated.');
        return ['#task-'.$task->id => $this->makePartial('task', ['task' => $task])];
      } else {
        Flash::success('Task has been created.');
        return ['group' => $task->task_groups_id, 'task' => '<div class="task" id="task-'.$task->id.'" data-id="'.$task->id.'">'.$this->makePartial('task', ['task' => $task]).'</div>'];
      }
    }


    public function onTasksSave()
    {
      $group_id = Request::input('group');
      $tasks = Request::input('tasks',[]);

      $i = 0;
      foreach($tasks as $task) {
        TaskModel::where('id',$task)->update(['task_groups_id' => $group_id, 'order' => $i]);
        $i++;
      }

      return [
        '#header-tasks-'.$group_id.' .count' => TaskGroupsModel::find($group_id)->task()->count()
      ];
    }

    public function onGetProjectList()
    {
      $this->vars['projects'] = ProjectsModel::all();
    }

    public function onGetUsers()
    {
      $query = Request::input('query');

      $q = User::where('first_name','like','%'.$query.'%')
             ->orWhere('last_name','like','%'.$query.'%')
             ->orWhere('login','like','%'.$query.'%')
             ->where('is_activated',1)
             ->get();

      $users = array();
      foreach($q as $user) {
        $name = trim($user->first_name.' '.$user->last_name);
        $name = $user->login.($name != '' ? ' ('.$name.')' : '');
        array_push($users, ['id' => $user->id, 'name' => $name]);
      }

      return Response::json($users);
    }
}
