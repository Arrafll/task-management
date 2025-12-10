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
</style>


<body>
  <div class="container py-5">
    <div class="card p-4">
      <div class="justify-content-between align-items-center mb-4">
        <div class="row col-lg-12">
          <h4 class="fw-bold mb-0">Project List</h4>
          <small class="text-muted">List seluruh project</small>
        </div>
        <form method="GET" id="search-project"></form>
        <div class="row col-lg-12 mt-3">
          <div class="col-lg-9">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProjectModal">Add +</button>
          </div>
          <div class="col-lg-3 d-flex">
            <input type="text" class="form-control" placeholder="Search Project" style="min-width: 240px;" name="search" form="search-project">
            <button class="btn btn-outline-primary" form="search-project">Search</button>
          </div>
        </div>
      </div>

      <table class="table align-middle">
        <thead class="text-muted">
          <tr>
            <th>Project Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          @foreach ($projects as $project)
          <tr>
            <td><a href="{{ route('project.detail', $project->id) }}" style="text-decoration: none;">{{ $project->name }}</a></td>
            <td>{{ \Carbon\Carbon::parse($project->start_date)->format('d-m-Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($project->end_date)->format('d-m-Y') }}</td>
            <td><span class="badge rounded-pill bg-{{ getProjectStatLabel($project->status) }}">{{ getProjectStatDesc($project->status) }}</span></td>
            <td class="w-10">
              <div class="dropdown">
                <button class="btn btn-sm border-0" type="button" data-bs-toggle="dropdown">
                  <i class="bi bi-three-dots"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="{{ route('project.detail', $project->id) }}">View Detail</a></li>
                  <li><a class="dropdown-item" href="#" onclick="editProjectPop({id: '{{ $project->id }}',name: '{{ $project->name }}',status: '{{ $project->status }}',startDate: '{{ $project->start_date }}',endDate: '{{ $project->end_date }}'})">Edit</a></li>
                  <li><a class="dropdown-item text-danger" onclick="warnDeleteNotif('{{ $project->id }}')">Delete</a></li>
                </ul>
              </div>
            </td>
          </tr>
          @endforeach
          @if (!$projects->count())
          <td colspan="5" class="text-center">No data</td>
          @endif
        </tbody>
      </table>
      <!-- Pagination -->
      <div class="d-flex justify-content-between mt-2">
        <div>
          Showing {{ $projects->firstItem() }} to {{ $projects->lastItem() }} of {{ $projects->total() }} entries
        </div>
        <div>
          {{ $projects->links('layouts.pagination') }}
        </div>
      </div>

    </div>
  </div>

  <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="projectAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="projectAddModalLabel">New Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('project.create') }}">
            @csrf
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label">Name</label>
              <input type="text" class="form-control" name="name" placeholder="Input project name">
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label">Start Date</label>
              <input id="startDate" class="form-control" name="start_date" type="date" />
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label">End Date</label>
              <input id="startDate" class="form-control" name="end_date" type="date" />
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
  <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="projectEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="projectEditModalLabel">Edit Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('project.update') }}">
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
              <label for="" class="col-form-label">Start Date</label>
              <input id="projectStartDate" class="form-control" name="start_date" type="date" />
            </div>
            <div class="mb-3">
              <label for="" class="col-form-label">End Date</label>
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
  <script>
    function warnDeleteNotif(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "Data will be lost!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `/project-delete/${id}`;
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
  </script>
  @endsection