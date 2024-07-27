<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo Groups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .completed {
            text-decoration: line-through;
            color: grey;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-4">
        <h1>Todo Groups</h1>

        <form action="{{ route('groups.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="New Group Name" required>
                <button type="submit" class="btn btn-primary">Add Group</button>
            </div>
        </form>

        @forelse($groups as $group)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>{{ $group->name }}</h2>
                    <form action="{{ route('groups.destroy', $group) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete Group</button>
                    </form>
                </div>
                <div class="card-body">
                    <h3>Todos:</h3>
                    <ul class="list-group">
                        @forelse($group->todos as $todo)
                            <li class="list-group-item d-flex justify-content-between align-items-center {{ $todo->completed ? 'completed' : '' }}">
                                <span class="todo-title">{{ $todo->completed ? '<strike>' . $todo->title . '</strike>' : $todo->title }}</span>
                                <span>
                                    <button class="btn btn-sm btn-success complete-btn" data-todo-id="{{ $todo->id }}">Complete</button>
                                    <button class="btn btn-sm btn-primary edit-btn" onclick="toggleEditForm({{ $todo->id }})">Edit</button>
                                    <form action="{{ route('todos.destroy', $todo) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </span>
                            </li>
                            <!-- Inline Edit Form -->
                            <li class="list-group-item edit-form" id="edit-form-{{ $todo->id }}" style="display: none;">
                                <form action="{{ route('todos.update', $todo) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="input-group">
                                        <input type="text" name="title" class="form-control" value="{{ $todo->title }}" required>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-secondary" onclick="toggleEditForm({{ $todo->id }})">Cancel</button>
                                    </div>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item">No todos in this group</li>
                        @endforelse
                    </ul>
                    <form action="{{ route('todos.store') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                        <div class="input-group">
                            <input type="text" name="title" class="form-control" placeholder="New Todo" required>
                            <button type="submit" class="btn btn-success">Add Todo</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <p>No groups created yet.</p>
        @endforelse
    </div>

    <script>
        function toggleEditForm(todoId) {
            const editForm = document.getElementById(`edit-form-${todoId}`);
            editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.complete-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    let todoId = this.getAttribute('data-todo-id');
                    let form = this.closest('form');
                    let li = this.closest('li');
                    let todoTitle = li.querySelector('.todo-title');
                    fetch(`/todos/${todoId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ completed: true })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.completed === 'yes') {
                                li.classList.add('completed');
                                todoTitle.innerHTML = '<s>' + todoTitle.innerText + '</s>';
                                alert('Completed successfully');
                            } else {
                                li.classList.remove('completed');
                                todoTitle.innerHTML = todoTitle.innerText.replace(/<\/?s>/g, '');
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
</body>
</html>
