<!-- Task List -->
<div class="card p-4 mb-4">

    <div class="justify-content-between mb-3">
        <div>
            <h5 class="fw-bold">Tasks</h5>
            <small class="text-muted">List of all tasks in this project</small>
        </div>

        <button class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#addTaskModal">
            Add Task +
        </button>
    </div>

    <table class="table align-middle">
        <thead class="text-muted">
            <tr>
                <th>Title</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Deadline</th>
                <th>Description</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>

                <td>
                    <span class="badge bg-{{ getTaskPriorLabel($task->priority) }}">
                        {{ getTaskPriorDesc($task->priority) }}
                    </span>
                </td>
                <td>
                    <span class="badge bg-{{ getTaskStatLabel($task->status) }}">
                        {{ getTaskStatDesc($task->status) }}
                    </span>
                </td>


                <td>
                    {{ \Carbon\Carbon::parse($task->deadline)->format('d-m-Y') }}
                    @if ($task->deadline < now() && $task->status != 4)
                        <span class="badge bg-danger">Overdue</span>
                        @endif
                </td>
                <td>{{ $task->description }}</td>

                <td class="text-end">
                    <div class="dropdown">
                        <button class="btn btn-sm border-0" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item"
                                    onclick="changeStatusPop('{{ $task->id }}')">Change status</button></li>

                            <li><button class="dropdown-item"
                                    onclick="editTaskPop({id: '{{ $task->id }}',title: '{{ $task->title }}',priority: '{{ $task->priority }}', status: '{{ $task->status }}',deadline: '{{ $task->deadline }}', description: '{{ $task->description }}'})">Edit</button></li>

                            <li><button class="dropdown-item text-danger"
                                    onclick="warnDeleteTask('{{ $task->id }}')">
                                    Delete
                                </button></li>
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach

            @if ($tasks->isEmpty())
            <tr>
                <td colspan="5" class="text-center text-muted">No tasks</td>
            </tr>
            @endif
        </tbody>
    </table>

</div>
<div class="modal fade" id="changeStatusTask" tabindex="-1" aria-labelledby="taskChangeStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskChangeStatusLabel">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('task.change.status') }}" id="changeStatusForm">
                    @csrf
                    <input type="hidden" name="id" id="statusTaskId">
                    <div class="mb-3">
                        <label for="taskStatus" class="col-form-label">Status</label>
                        <select class="form-select" name="status" id="changeTaskStatus" required>
                            <option value="">Select Status</option>
                            <option value="1">Todo</option>
                            <option value="2">Doing</option>
                            <option value="3">Review</option>
                            <option value="4">Done</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" onclick="changeStatusAct()" class="btn btn-primary">Save</button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="taskEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskEditModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('task.update') }}">
                    @csrf
                    <input type="hidden" name="id" id="taskId">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Title</label>
                        <input type="text" class="form-control" name="title" id="taskTitle" placeholder="Input task title">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Deadline</label>
                        <input id="taskDeadline" class="form-control" name="deadline" type="date" />
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Priority</label>
                        <select class="form-select" name="priority" id="taskPriority">
                            <option value="">Select Priority</option>
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Status</label>
                        <select class="form-select" name="status" id="taskStatus">
                            <option value="">Select Status</option>
                            <option value="1">Todo</option>
                            <option value="2">Doing</option>
                            <option value="3">Review</option>
                            <option value="4">Done</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Description</label>
                        <textarea name="description" id="taskDescription" class="form-control" placeholder="Input task description"></textarea>
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

<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="taskAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskAddModalLabel">Add Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('task.create') }}">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Input task title">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Deadline</label>
                        <input id="startDate" class="form-control" name="deadline" type="date" />
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Priority</label>
                        <select class="form-select" name="priority" id="taskPriority">
                            <option value="">Select Priority</option>
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Description</label>
                        <textarea name="description" id="" class="form-control" placeholder="Input task description"></textarea>
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
    function editTaskPop(params) {

        const {
            id,
            title,
            priority,
            status,
            deadline,
            description
        } = params;
        console.log(params)

        $('#taskId').val(id);
        $('#taskTitle').val(title);
        $('#taskPriority').val(priority);
        $('#taskStatus').val(status);
        $('#taskDeadline').val(deadline);
        $('#taskDescription').val(description);

        $('#editTaskModal').modal('show');

    }

    function changeStatusPop(id) {
        $('#statusTaskId').val(id);
        $('#changeStatusTask').modal('show');

    }

    function changeStatusAct() {
        // validation val
        if ($('#changeTaskStatus').val() === "") {
            Swal.fire({
                title: 'Status Required',
                text: 'Please select a new status before continuing.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return; 
        }

        Swal.fire({
            title: 'Change Status?',
            text: 'Task status will be updated.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                $('#changeStatusForm').submit();
            }
        });
    }
</script>