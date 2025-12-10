<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class ProjectManagement extends Controller
{
    //Start of crud function project

    public function index(Request $request)
    {

        $searchVal = $request->input('search');

        $projectList =  Project::query()
            ->when($searchVal, function ($query, $searchVal) {
                $query->where(function ($q) use ($searchVal) {
                    $q->where('name', 'like', "%{$searchVal}%")
                        ->orWhere('status', 'like', "%{$searchVal}%")
                        ->orWhere('start_date', 'like', "%{$searchVal}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $data = [
            'projects' => $projectList,
            'title' => 'Project List'
        ];

        return view('project.project_list', $data);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:70',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        Project::create($validated);
        return redirect()->back()->with('successNotif', 'Project created successfully');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:70',
            'status'     => 'required',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $id = $request->input('id');
        $project = Project::findOrFail($id);
        $project->update($validated);

        return redirect()->back()->with('successNotif', 'Project updated successfully');
    }

    public function delete($id)
    {
        Project::findOrFail($id)->delete();
        return redirect()->back()->with('successNotif', 'Project deleted succesfully.');
    }

    //End of crud function project

    public function detail($id)
    {

        $project = Project::findOrFail($id);

        // get tasks in this project
        $tasks = Task::where('project_id', $id)->orderBy('created_at', 'desc')->get();


        // map all widgets in one function
        $widgets = $this->widgetDetail($id, $tasks);
        $data = [
            'project' => $project,
            'tasks' => $tasks,
            'widgets' => $widgets,
            'title' => 'Project Detail'
        ];
        return view('project.project_detail', $data);
    }


    // function get per month task done
    private function donePerMonthData($projectId)
    {
        $labels = [];
        $data   = [];

        // loop 12 months

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $labels[] = $month->format('M');

            $count = Task::where('project_id', $projectId)
                ->where('status', 4) // Done
                ->whereMonth('updated_at', $month->month)
                ->whereYear('updated_at', $month->year)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data'   => $data
        ];
    }

    // function count total, onprogress, overdue, and done also charts
    private function widgetDetail($id, $tasks)
    {

        $total = $tasks->count();
        $todo  = $tasks->where('status', 1)->count();
        $doing  = $tasks->where('status', 2)->count();
        $review  = $tasks->where('status', 3)->count();
        $done  = $tasks->where('status', 4)->count();

        $overdue = $tasks->filter(function ($task) {
            return $task->deadline < Carbon::now() && $task->status != 4;
        })->count();

        $onProgress  = $tasks->whereIn('status', [2, 3])->count();

        // progress bar
        $progress = $total == 0 ? 0 : ($done / $total * 100);

        // monthly done charts
        $monthlyDone = $this->donePerMonthData($id);

        // tasks per status charts
        $taskPerStatus = [
            ['name' => 'Todo', 'y' => $todo],
            ['name' => 'Doing', 'y' => $doing],
            ['name' => 'Review', 'y' => $review],
            ['name' => 'Done', 'y' => $done],
        ];


        $widgets = [
            'total_tasks'  => $total,
            'done'         => $done,
            'overdue'      => $overdue,
            'onProgress'   => $onProgress,
            'progressBar'  => $progress,
            'monthlyDoneCharts' => $monthlyDone,
            'taskPerStatus' => $taskPerStatus
        ];

        return $widgets;
    }
}
