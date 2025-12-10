<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

class TaskManagement extends Controller
{
    // Start Of Task Create, Update, Delete
    public function create(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required',
            'title'      => 'required|max:50',
            'priority'   => 'required|in:1,2,3',
            'deadline'   => 'required|date',
            'description' => 'required',

        ]);

        // update status project if theres new task
        $project = Project::findOrFail($request->project_id);
        if ($project->status == 1) {
            $project->update(['status' => 2]);
        }
        Task::create($validated);
        return redirect()->back()->with('successNotif', 'Task created successfully');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id'        => 'required',
            'title'     => 'required',
            'deadline'  => 'required|date',
            'priority'  => 'required|in:1,2,3',
            'status'    => 'required',
            'description' => 'required',
        ]);

        Task::findOrFail($request->id)->update($validated);
        return redirect()->back()->with('successNotif', 'Task updated succesfully');
    }

    public function delete($id)
    {
        Task::findOrFail($id)->delete();
        return redirect()->back()->with('successNotif', 'Task deleted successfully');
    }

    public function change_status(Request $request)
    {

        $id = $request->input('id');
        $status = $request->input('status');

        $task = Task::findOrFail($id);
        $task->update(['status' => $status]);
        return redirect()->back()->with('successNotif', 'Task status changed');
    }
}
