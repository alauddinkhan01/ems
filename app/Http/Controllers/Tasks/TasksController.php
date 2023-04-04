<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use File;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function index($id)
    {
        $departments = Departments::all();
        $user_id = $id;
        $tasks = Tasks::where(['user_id'=> $id,'status'=>'pending'])->with('user.department')->paginate(5);
        return Inertia::render('Tasks/Index', compact('departments', 'tasks', 'user_id'));
    }

    public function assignTask(Request $request)
    {
        $task = new Tasks();
        if ($request->file('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . str_replace(' ', '_', $request->title) . '.' . $extension;
            $file->move('tasks', $fileName);
            $task->image = $fileName;
        }

        $task->user_id = $request->employe_id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->save();
        return response()->json(['success' => 'Task assigned successfully!', 'status' => 200]);
    }

    public function updateTask(Request $request)
    {
        $task = Tasks::find($request->task_id);
        if ($request->file('image')) {
            if (File::exists(public_path('tasks/' . $task->image))) {
                File::delete(public_path('tasks/' . $task->image));
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . str_replace(' ', '_', $request->title) . '.' . $extension;
            $file->move('tasks', $fileName);
            $task->image = $fileName;
        }

        $task->title = $request->title;
        $task->status = $request->status;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->save();
        return response()->json(['success' => 'Task updated successfully!', 'status' => 200]);
    }

    public function deleteTask($id)
    {
        $task = Tasks::find($id);
        if ($task->image) {
            if (File::exists(public_path('tasks/' . $task->image))) {
                File::delete(public_path('tasks/' . $task->image));
            }
        }
        $task->delete();
        return response()->json(['success' => 'Task deleted successfully!', 'status' => 200]);
    }

    public function getTasks(Request $request)
    {
        $tasks = Tasks::where(['user_id' => $request->user_id, 'status' => $request->status])->with('user.department')->paginate(5);
        return response()->json(['tasks' => $tasks, 'status' => 200]);
    }

    public function myTasks()
    {
        $tasks = Tasks::where(['user_id'=>Auth::user()->id, 'status'=>'pending'])->with('user.department')->paginate(5);
        return Inertia::render('Tasks/Index', compact('tasks'));
    }

    public function changeStatus(Request $request,$id)
    {
        $task = Tasks::find($id);
        $task->status = $request->status;
        $task->save();
        return response()->json(['success'=>'Status changed successfully!', 'status'=>true]);
    }
}
