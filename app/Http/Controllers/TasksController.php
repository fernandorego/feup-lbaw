<?php

namespace App\Http\Controllers;

use App\Models\ProjectUser;
use App\Models\Task;
use App\Models\Project;
use App\Models\UserAssign;
use App\Models\TaskComment;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TasksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showTaskForm(int $id)
    {
        Gate::authorize('manager',Project::find($id));
        $notifications = NotificationsController::getNotifications(Auth::id());

        $users = ProjectUsersController::getProjectUsers($id);

        return view('pages.tasksCreate',['project' => Project::find($id), 'notifications' => $notifications, 'users' => $users]);
    }

    protected function validator()
    {
        return  [
            'name' => ['required','string'],
            'description' => ['required','string'],
        ];
    }

    public function showTask(int $project_id, int $id)
    {
        Gate::authorize('show',Task::find($id));
        $notifications = NotificationsController::getNotifications(Auth::id());

        $users = ProjectUsersController::getProjectUsers($project_id);

        $taskComments = TaskCommentsController::getTaskComments($id);

        $user_assigned = DB::table('userassigns')
                            ->leftjoin('users', 'users.id', '=', 'userassigns.user_id')
                            ->where('task_id', '=', $id)
                            ->get(['userassigns.user_id','users.username']);

        $user_assigned = json_decode($user_assigned, true);

        $user_role = ProjectUser::find(['user_id' => Auth::id(),'project_id' => $project_id])->user_role;

        return view('pages.task',['user_role' => $user_role,
                                    'project' => Project::find($project_id),
                                    'task' => Task::find($id), 
                                    'notifications' => $notifications,
                                    'users' => $users,
                                    'user_assigned' => $user_assigned,
                                    'task_comments' => $taskComments]);
    }


    public function updateTask(int $project_id, int $id, Request $request) {

        $notifications = NotificationsController::getNotifications(Auth::id());

        switch ($request->input('action'))
        {
            case 'update':
                Gate::authorize('update',Task::find($id));
                $validator = $request->validate($this->validator());
                try {
                    $task = Task::find($id);
                    $task->name = $request->name;
                    $task->description = $request->description;
                    $task->due_date = $request->due_date;
                    $task->tag = $request->tag;
                    $task->save();
                } catch (QueryException $e){
                    return redirect()->back()->withErrors('Due and reminder dates are both required. You should also verify that selected reminder date is before due date and that both after today.');
                }


                if ($request->user_id == -1){
                    $user_assigned = UserAssign::where('task_id', '=', $id)
                                            ->delete(['user_id' =>$request->user_id]);
                    break;
                }

                $user_assigned = UserAssign::where('task_id', '=', $id)
                                            ->update(['user_id' =>$request->user_id]);

                $user_assigned = json_decode($user_assigned, true);

                if (!$user_assigned){
                    $user_assign = new UserAssign;
                    $user_assign->task_id = $task->id;
                    $user_assign->user_id = $request->user_id;
                    $user_assign->save();
                }

                break;

            case 'delete':
                Gate::authorize('manager',Project::find($project_id));
                $task=Task::find($id);
                $task->delete(); //returns true/false
        }

        return redirect('project/'.$project_id.'/tasks');
    }

    public function showTasks($project_id)
    {
        Gate::authorize('notGuest',Project::find($project_id));
        $notifications = NotificationsController::getNotifications(Auth::id());
        $users = ProjectUsersController::getProjectUsers($project_id);
        $user_role = ProjectUser::find(['user_id' => Auth::id(),'project_id' => $project_id])->user_role;

        $my_TASKS = DB::table('tasks')
                        ->leftjoin('userassigns', 'tasks.id', '=', 'userassigns.task_id')
                        ->leftjoin('users', 'users.id', '=', 'userassigns.user_id')
                        ->where('tasks.project_id', $project_id)
                        ->orderby('tasks.due_date')
                        ->get(['tasks.id','name','description','due_date','username', 'tasks.tag']);
        $my_TASKS = json_decode($my_TASKS,true);

        /*
        $my_TODO = DB::table('tasks')
                        ->leftjoin('userassigns', 'tasks.id', '=', 'userassigns.task_id')
                        ->leftjoin('users', 'users.id', '=', 'userassigns.user_id')
                        ->where('tasks.project_id', $project_id)
                        ->where('tasks.tag', 'TODO')
                        ->get(['tasks.id','name','description','due_date','username']);
        $my_TODO = json_decode($my_TODO,true);
        //dd($my_TODO);
        $my_DOING = DB::table('tasks')
                        ->leftjoin('userassigns', 'tasks.id', '=', 'userassigns.task_id')
                        ->leftjoin('users', 'users.id', '=', 'userassigns.user_id')
                        ->where('tasks.project_id', $project_id)
                        ->where('tasks.tag', 'DOING')
                        ->get(['tasks.id', 'name','description','due_date','username']);
        $my_DOING = json_decode($my_DOING,true);
        //dd($my_DOING);
        $my_REVIEW = DB::table('tasks')
                        ->leftjoin('userassigns', 'tasks.id', '=', 'userassigns.task_id')
                        ->leftjoin('users', 'users.id', '=', 'userassigns.user_id')
                        ->where('tasks.project_id', $project_id)
                        ->where('tasks.tag', 'REVIEW')
                        ->get(['tasks.id', 'name','description','due_date','username']);
        $my_REVIEW = json_decode($my_REVIEW,true);
        //dd($my_REVIEW);
        $my_CLOSED = DB::table('tasks')
                        ->leftjoin('userassigns', 'tasks.id', '=', 'userassigns.task_id')
                        ->leftjoin('users', 'users.id', '=', 'userassigns.user_id')
                        ->where('tasks.project_id', $project_id)
                        ->where('tasks.tag', 'CLOSED')
                        ->get(['tasks.id', 'name','description','due_date','username']);
        $my_CLOSED = json_decode($my_CLOSED,true);
        //dd($my_CLOSED);
        return view('pages.tasks',['tasks_TODO' => $my_TODO, 'tasks_DOING' => $my_DOING,'tasks_REVIEW' => $my_REVIEW,'tasks_CLOSED' => $my_CLOSED,'project' => Project::find($project_id), 'notifications' => $notifications]);

        return view('pages.tasks',['tasks' => $my_TASKS, 'tasks', 'tasks_TODO' => $my_TODO, 'tasks_DOING' => $my_DOING,'tasks_REVIEW' => $my_REVIEW,'tasks_CLOSED' => $my_CLOSED,'project' => Project::find($project_id)]);
        */

        return view('pages.tasks',['user_role' => $user_role,'tasks' => $my_TASKS, 'tasks', 'project' => Project::find($project_id), 'notifications' => $notifications, 'users' => $users]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     *
     */
    protected function create(Request $request)
    {
        Gate::authorize('manager',Project::find($request->project_id));
        try {
            $notifications = NotificationsController::getNotifications(Auth::id());
            $validator = $request->validate($this->validator());
            $task = new Task;
            $task->name = $request->name;
            $task->description = $request->description;
            $task->due_date = $request->due_date;
            $task->reminder_date = $request->reminder_date;
            $task->tag = $request->tag;
            $task->project_id = $request->project_id;
            $task->creator_id = Auth::id();
            $task->created_at = Carbon::now();
            $task->save();

            if ($request->user_id != -1) {
                $user_assign = new UserAssign;
                $user_assign->task_id = $task->id;
                $user_assign->user_id = $request->user_id;
                $user_assign->save();
            }
        } catch (QueryException $e){
            return redirect()->back()->withErrors('Due and reminder dates are both required. You should also verify that selected reminder date is before due date and that both after today.');
        }


return redirect()->action([TasksController::class,'showTasks'], ['id'=> $task->project_id]);
    }
/*
    static function getProjectTasks($project_id) {
        return (new Task())->where('project_id','=',$project_id)->orderBy('due_date');
    }
*/
    public function searchProjectTasks(int $project_id, Request $request)
    {
        return DB::table('tasks')
                            ->where('project_id', '=', $project_id)
                            ->whereRaw("(name like '%".$request->search."%'
                                                or description like '%".$request->search."%'
                                                or CAST(due_date AS VARCHAR) like '%".$request->search."%'
                                                or CAST(tag AS VARCHAR) like '".$request->search."%')")
                            ->orderBy('due_date')
                            ->get();
    }
}
