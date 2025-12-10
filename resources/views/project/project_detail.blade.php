@extends('layouts.main')
@section('content')

<style>
    body {
        background-color: #f7f7f9;
        font-family: 'Inter', sans-serif;
    }


    .card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.08);
    }

    .stat-card {
        border-radius: 14px;
        padding: 18px 20px;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
    }
</style>

<div class="container py-4">

    <div class="row mb-4">
        <div class="col-lg-12">
            <h4 class="fw-bold">Project Detail</h4>
            <small class="text-muted">Complete information along with tasks and statistics</small>
        </div>
    </div>
    <div class="mb-3">
        <a href="{{ route('project.list') }}" class="btn btn-secondary">
            Project List
        </a>
    </div>
    <!-- Project Information -->
    <div class="card p-4 mb-4">
       <!-- Condition problematic project if overdue task exist and progress below 50 (hardcoded) -->
        @if($widgets['overdue'] > 0 || $widgets['progressBar'] < 50)
            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>
                    Project ini dikategorikan bermasalah karena terdapat task overdue
                    dan progress keseluruhan masih rendah (di bawah 50%).
                </span>
            </div>
        @endif
    <div class="d-flex justify-content-between">
        <div>
            <h4 class="fw-bold mb-1">{{ $project->name }}</h4>
            <span class="badge bg-{{ getProjectStatLabel($project->status) }}">
                {{ getProjectStatDesc($project->status) }}
            </span>

            <div class="mt-3 col-lg-12">
                <p class="mb-1"><strong>Start Date:</strong>
                    {{ \Carbon\Carbon::parse($project->start_date)->format('d-m-Y') }}
                </p>
                <p class="mb-1"><strong>End Date:</strong>
                    {{ \Carbon\Carbon::parse($project->end_date)->format('d-m-Y') }}
                </p>
            </div>
        </div>


        <div>
            <button class="btn btn-outline-secondary"
                onclick="editProjectPop({
                            id:'{{ $project->id }}',
                            name:'{{ $project->name }}',
                            status:'{{ $project->status }}',
                            startDate:'{{ $project->start_date }}',
                            endDate:'{{ $project->end_date }}'
                        })">
                Edit Project
            </button>
        </div>
    </div>

    <div class="col-md-12">

        <p class="mb-1"><strong>Progress:</strong>
        <div class="progress w-100">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{$widgets['progressBar']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $widgets['progressBar'] }}%">{{ round($widgets['progressBar']) }}%</div>
        </div>
        </p>
    </div>
</div>

<!-- STATS -->
<div class="row mb-4">

    <div class="col-lg-3 mb-3">
        <div class="stat-card">
            <h6 class="text-muted mb-1">Task Total</h6>
            <h3 class="fw-bold">{{ $widgets['total_tasks'] }}</h3>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="stat-card">
            <h6 class="text-muted mb-1">Task Done</h6>
            <h3 class="fw-bold">{{ $widgets['done'] }}</h3>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="stat-card">
            <h6 class="text-muted mb-1">Task Overdue</h6>
            <h3 class="fw-bold text-danger">{{ $widgets['overdue'] }}</h3>
        </div>
    </div>

    <div class="col-lg-3 mb-3">
        <div class="stat-card">
            <h6 class="text-muted mb-1">Task On Progress</h6>
            <h3 class="fw-bold">
                {{ $widgets['onProgress'] }}
            </h3>
        </div>
    </div>


</div>



<!-- Widget Chartss -->
<div class="mb-5 row">
    <div class="col-lg-6">
        <div class="stat-card">
            <h5 class="fw-bold mb-3">Task Done per Month</h5>
            <div id="monthlyDoneCharts" style="width:100%; height:400px;"></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="stat-card">
            <h5 class="fw-bold mb-3">Task by Status</h5>
            <div id="taskStatusChart" style="width:100%; height:400px;"></div>
        </div>
    </div>
</div>

@include('task.task_list');

</div>
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="projectEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectEditModalLabel">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('project.update') }}">
                    @csrf
                    <input type="hidden" name="id" id="projectId">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="projectName" placeholder="Input project name">
                    </div>
                    <div class="mb-3">
                        <label for="projectStatus" class="col-form-label">Status</label>
                        <select class="form-select" name="status" id="projectStatus">
                            <option value="1">Planning</option>
                            <option value="2">On Progress</option>
                            <option value="3">Done</option>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Start Date</label>
                        <input id="projectStartDate" class="form-control" name="start_date" type="date" />
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">End Date</label>
                        <input id="projectEndDate" class="form-control" name="end_date" type="date" />
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.highcharts.com/highcharts.js"> </script>
<script>
    function warnDeleteTask(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Task will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/task-delete/${id}`;
            }
        });
    }

    function editProjectPop(params) {

        const {
            id,
            name,
            status,
            startDate,
            endDate
        } = params;


        $('#projectId').val(id);
        $('#projectName').val(name);
        $('#projectStatus').val(status);
        $('#projectStartDate').val(startDate);
        $('#projectEndDate').val(endDate);

        $('#editProjectModal').modal('show');

    }

    $(document).ready(function() {

        // parse string json js, text editor blade error
        let xAxisMonthly = JSON.parse(`{!! json_encode($widgets['monthlyDoneCharts']['labels']) !!}`)
        let yAxisMonthly = JSON.parse(`{!! json_encode($widgets['monthlyDoneCharts']['data']) !!}`)

        const chart = Highcharts.chart('monthlyDoneCharts', {
            chart: {
                type: 'column'
            },
            title: {
                text: `Year {{date('Y')}}`
            },
            xAxis: {
                categories: xAxisMonthly
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total Done'
                }
            },

            series: [{
                name: 'Done',
                data: yAxisMonthly
            }]
        });
    });

    let chartPerStatusData = JSON.parse(`{!! json_encode($widgets['taskPerStatus']) !!}`)

    Highcharts.chart('taskStatusChart', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Tasks by Status'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
                }
            }
        },
        series: [{
            name: 'Tasks',
            colorByPoint: true,
            data: chartPerStatusData
        }]
    });
</script>

@endsection