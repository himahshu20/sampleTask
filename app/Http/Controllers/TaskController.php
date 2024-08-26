<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Yajra\DataTables\DataTables;

class TaskController extends Controller
{
    //

    public function index(Request $request){
        return view('pages.task');
    }

    public function getTask(Request $request){
        {
            $req_data = $request->all();
            $status = $req_data['status_'];
            if ($status == 'all') {
                $data = Task::all();
            } else {
                $data = Task::where('Status', 1);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = NULL;
                    if($row['Status']==1){
                        $btn = '<a href="javascript:void(0)" task='.$row->id.' class="status btn btn-info btn-sm">Incomplete</a> ';
                    }
                    if($row['Status']==0){
                        $btn = '<label class="status btn btn-success btn-sm">Completed</label> ';
                    }
                    $btn .= '<a href="javascript:void(0)" task='.$row->id.' class="delete btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function saveTask(Request $request){
        {
        $task = new Task();
        $task->name = $request->input('name');
        $task->Status = 1;//1 for incomplete 0 For completed 
        $task->save();

        return response()->json(['success' => true]);

        }
    }
    public function updateTask(Request $request){
        $id = $request->all('task');
        try {
            $validated = $request->validate([
                'task' => 'required|string|max:255',
            ]);
            $task = Task::where('id',$id)->first();
            if (!$task) {
                return response()->json(['success' => false, 'message' => 'Task not found'], 404);
            }
            $task->Status = 0;
            $updated = $task->save();
            return response()->json(['success' => $updated]);
        } catch (\Exception $e) {
            \Log::error('Update Task Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the task'], 500);
        }
    }
    public function deleteTask(Request $request){
        $id = $request->all('task');
        try {
            $validated = $request->validate([
                'task' => 'required|string|max:255',
            ]);
            $task = Task::where('id',$id)->first();
            if (!$task) {
                return response()->json(['success' => false, 'message' => 'Task not found'], 404);
            }
            $updated = $task->delete();
            return response()->json(['success' => $updated]);
        } catch (\Exception $e) {
            \Log::error('Update Task Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the task'], 500);
        }
    }
}
