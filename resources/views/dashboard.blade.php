@extends('layout')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-tasks me-2"></i>Task List
                    </h5>

                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-outline-primary btn-sm mr-2" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus me-1"></i> Add Task
                        </button>

                        <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm">
                            Logout
                        </a>
                    </div>

                </div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">

                        <div class="d-flex gap-2 mb-2">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="searchTitle" class="form-control" placeholder="Search by title" aria-label="Search" aria-describedby="basic-addon1">
                            </div>

                            <select id="statusFilter" class="form-select" style="max-width: 200px;">
                                <option value="">All Status</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <input type="date" id="from_date" class="form-control" placeholder="From date">
                            <input type="date" id="to_date" class="form-control" placeholder="To date">
                            <button type="button" id="filterDate" class="btn btn-primary">Filter</button>
                            <button type="button" id="clearFilter" class="btn btn-secondary">Clear</button>
                        </div>

                        <div id="filterErrors" class="alert alert-danger d-none mt-2"></div>
                    </div>

                    <table class="table no-top-border text-dark mb-0">
                        <thead>
                            <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="width: 160px;">Due date</th>
                            <th scope="col" style="width: 160px;">Created date</th>
                            <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="todoTable">
                            @forelse ($todos as $todo)
                                <tr class="fw-normal" id="todo-{{ $todo->id }}">
                                    <td class="align-middle">
                                        <span>{{ $todo->title }}</span>
                                    </td>
                                    <td class="align-middle text-muted">
                                        {{ $todo->description ?? '' }}
                                    </td>
                                    <td class="align-middle">
                                        <h6 class="mb-0"><span class="badge 
                                        {{ $todo->status == 'Pending' ? 'bg-warning' : ($todo->status == 'In Progress' ? 'bg-primary' : 'bg-success') }}">
                                        {{ $todo->status }}</span></h6>
                                    </td>
                                    <td class="align-middle">
                                        @if($todo->due_date)
                                            <p class="small mb-0 text-warning"><i class="text-warning fas fa-hourglass-half me-2 text-warning"></i>{{ $todo->due_date ? $todo->due_date->format('d M Y') : '-' }}</p>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <p class="small mb-0 text-muted"><i class="text-muted fas fa-info-circle me-2"></i>{{ $todo->created_at->format('d M Y') }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($todo->status != 'Completed')
                                            <i href="#!" title="Done" class="fas fa-check fa-lg text-success p-1 completeTodo" data-id="{{ $todo->id }}"></i>
                                            <i href="#!" title="Edit" class="fas fa-pencil-alt fa-lg text-primary p-1 editTodo" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $todo->id }}"></i>
                                            @endif
                                            <i href="#!" title="Remove" class="fas fa-trash-alt fa-lg text-danger p-1 deleteTodo" data-id="{{ $todo->id }}"></i>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="noTodos">
                                    <td colspan="6" class="text-center text-muted">No tasks yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $todos->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Add Task</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="todoForm">
                
                <div class="modal-body">
                    <div id="todoErrors" class="alert alert-danger d-none"></div>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="" selected>---Choose---</option>
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editTodoForm">
                <input type="hidden" id="edit_id">

                <div class="modal-body">
                    <div id="editErrors" class="alert alert-danger d-none"></div>

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" id="edit_title" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="edit_description" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="edit_status" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="date" id="edit_due_date" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function(){

    let today = new Date().toISOString().split('T')[0];
    $('#due_date').attr('min', today);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#todoForm').submit(function(e){
        e.preventDefault();

        $('#todoErrors').addClass('d-none').html('');

        $.ajax({
            url: "{{ route('todos.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res){

                console.log(res);
                let todo = res.todo;

                $('#noTodos').remove();
                $('#todoTable').prepend(`
                    <tr class="fw-normal" id="todo-${todo.id}">
                        <td class="align-middle"><span>${todo.title}</span></td>
                        <td class="align-middle text-muted">${todo.description ?? ''}</td>
                        <td class="align-middle"><h6 class="mb-0"><span class="badge ${todo.status == 'Pending' ? 'bg-warning' : (todo.status == 'In Progress' ? 'bg-primary' : 'bg-success')}">${todo.status}</span></h6></td>
                        <td class="align-middle">${todo.due_date
                            ? `<p class="small mb-0 text-warning">
                                    <i class="fas fa-hourglass-half me-2"></i>${todo.due_date}
                            </p>`
                            : `<span>-</span>`}
                        </td>
                        <td class="align-middle">
                            <p class="small mb-0 text-muted"><i class="text-muted fas fa-info-circle me-2"></i>${todo.created_at}</p></td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center gap-3">
                                <i title="Done" class="fas fa-check fa-lg text-success p-1 completeTodo" data-id="${todo.id}"></i>
                                <i title="Edit" class="fas fa-pencil-alt fa-lg text-primary p-1 editTodo" data-id="${todo.id}"></i>
                                <i title="Remove" class="fas fa-trash-alt fa-lg text-danger p-1 deleteTodo" data-id="${todo.id}"></i>
                            </div>
                        </td>
                    </tr>
                `);

                $('#todoForm')[0].reset();
                $('#addModal').modal('hide');
            },
            error: function(xhr){
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    let html = '<ul>';

                    $.each(errors, function(key, value){
                        html += `<li>${value[0]}</li>`;
                    });

                    html += '</ul>';
                    $('#todoErrors').removeClass('d-none').html(html);
                }
            }
        });
    });

    $(document).on('click', '.fa-check', function(e){
        e.preventDefault();

        let row = $(this).closest('tr');
        let id = row.find('.deleteTodo').data('id');

        $.ajax({
            url: `/todos/${id}/complete`,
            type: 'PATCH',
            success: function(res){
                console.log(res);
                if(res.success){
                    let badge = row.find('span.badge');
                    badge.text(res.todo.status);
                    badge.removeClass('bg-warning bg-primary bg-success').addClass('bg-success');

                    row.find('.fa-check').remove();
                    row.find('.fa-pencil-alt').remove();
                }
            }
        });
    });

    $(document).on('click', '.editTodo', function () {
        let id = $(this).data('id');

        $.get(`/todos/${id}`, function (todo) {
            $('#edit_id').val(todo.id);
            $('#edit_title').val(todo.title);
            $('#edit_description').val(todo.description);
            $('#edit_status').val(todo.status);
            $('#edit_due_date').val(todo.due_date);
            $('#edit_due_date').attr('min', today);

            $('#editModal').modal('show');
        });
    });

    $('#editTodoForm').submit(function (e) {
        e.preventDefault();

        let id = $('#edit_id').val();

        $.ajax({
            url: `/todos/${id}`,
            type: 'PUT',
            data: {
                title: $('#edit_title').val(),
                description: $('#edit_description').val(),
                status: $('#edit_status').val(),
                due_date: $('#edit_due_date').val(),
            },
            success: function (res) {
                let row = $(`#todo-${id}`);

                row.find('td:eq(0) span').text(res.todo.title);
                row.find('td:eq(1)').text(res.todo.description ?? '');

                let badge = row.find('.badge');
                badge.text(res.todo.status)
                    .removeClass('bg-warning bg-primary bg-success')
                    .addClass(
                        res.todo.status === 'Pending' ? 'bg-warning' :
                        res.todo.status === 'In Progress' ? 'bg-primary' : 'bg-success'
                    );

                let dueDateText = res.todo.due_date
                    ? res.todo.due_date_formatted   
                    : '-';

                row.find('td:eq(3)').html(res.todo.due_date
                    ? `<p class="small mb-0 text-warning">
                        <i class="fas fa-hourglass-half me-2"></i>${res.todo.due_date_formatted || res.todo.due_date}
                    </p>`
                    : `<span>-</span>`
                );

                $('#editModal').modal('hide');
            }
        });
    });

    $(document).on('click', '.deleteTodo', function(){
        let id = $(this).data('id');

        $.ajax({
            url: `/todos/${id}`,
            type: 'DELETE',
            success: function(){
                $('#todo-' + id).remove();
            }
        });
    });

    function fetchTodos(params = {}) {
        $.get('{{ route("dashboard") }}', params, function(data){
            $('#todoTable').html($(data).find('#todoTable').html());
            $('.d-flex.justify-content-center').html($(data).find('.d-flex.justify-content-center').html());
        });
    }

    $('#filterDate').on('click', function() {
        let from = $('#from_date').val();
        let to = $('#to_date').val();
        let status = $('#statusFilter').val();
        let search = $('#searchTitle').val();

        if (!from || !to) {
            $('#filterErrors')
                .removeClass('d-none')
                .text('Please select both From and To dates.');
            return; 
        }

        $('#filterErrors').addClass('d-none').text('');

        fetchTodos({
            from_date: from,
            to_date: to,
            status: status,
            search: search
        });
    });

    $('#searchTitle').on('keyup', function () {
        fetchTodos({
            search: $(this).val(),
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val(),
            status: $('#statusFilter').val()
        });
    });

    $('#clearFilter').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
        $('#statusFilter').val('');
        $('#searchTitle').val('');
        fetchTodos({});
    });

    $('#statusFilter').on('change', function() {
        fetchTodos({
            search: $('#searchTitle').val(),
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val(),
            status: $(this).val()
        });
    });

});
</script>
@endsection

