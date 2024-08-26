<!DOCTYPE html>
<html>

<head>
    <title>Task List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container">
        <h1>List</h1>

        <form id="task-form">
            <div class="form-group">
                <label for="task-name">Name</label>
                <input type="text" id="task-name" name="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>

        <br>

        <button id="toggle-view" class="btn btn-secondary">Show All Tasks</button>

        <br><br>

        <table class="table table-bordered" id="task-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Task Name</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#task-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('getTask', ['status ' => 1]) }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#task-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('saveTask') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        table.ajax.reload();
                        $('#task-form')[0].reset();
                        toastr.success(response.message, 'Task has been added successfully');
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.status', function() {
                var task_id = $(this).attr('task');
                $.ajax({
                    url: '{{ route('updateTask') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    data: {
                        task: task_id
                    },
                    success: function(response) {
                        table.ajax.reload();
                        toastr.success(response.message, 'Task Status has been updated');
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.delete', function() {
                if (confirm('Are you sure you want to delete this task?')) {
                    var task_id = $(this).attr('task');
                    $.ajax({
                        url: '{{ route('deleteTask') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        data: {
                            task: task_id
                        },
                        success: function(response) {
                            toastr.success(response.message, 'Task Status');
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                        }
                    });
                } else {
                    console.log('Delete action was cancelled.');
                }
            });


            $('#toggle-view').on('click', function() {
                var isShowingAll = $(this).data('showingAll');

                if (isShowingAll) {
                    table.ajax.url('{{ route('getTask', ['status ' => 1]) }}').load();
                    $(this).text('Show All Tasks').data('showingAll', false);
                } else {
                    table.ajax.url('{{ route('getTask', ['status ' => 'all ']) }}').load();
                    $(this).text('Show Incomplete Tasks').data('showingAll', true);
                }
            });
        });
    </script>
</body>

</html>