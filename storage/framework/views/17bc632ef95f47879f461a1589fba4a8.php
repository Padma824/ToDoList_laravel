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
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body>
    <div class="container mt-4">
        <h1>Todo Groups</h1>

        <form action="<?php echo e(route('groups.store')); ?>" method="POST" class="mb-4">
            <?php echo csrf_field(); ?>
            <div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="New Group Name" required>
                <button type="submit" class="btn btn-primary">Add Group</button>
            </div>
        </form>

        <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><?php echo e($group->name); ?></h2>
                    <form action="<?php echo e(route('groups.destroy', $group)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">Delete Group</button>
                    </form>
                </div>
                <div class="card-body">
                    <h3>Todos:</h3>
                    <ul class="list-group">
                        <?php $__empty_2 = true; $__currentLoopData = $group->todos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center <?php echo e($todo->completed ? 'completed' : ''); ?>">
                                <span class="todo-title"><?php echo e($todo->completed ? '<strike>' . $todo->title . '</strike>' : $todo->title); ?></span>
                                <span>
                                    <button class="btn btn-sm btn-success complete-btn" data-todo-id="<?php echo e($todo->id); ?>">Complete</button>
                                    <button class="btn btn-sm btn-primary edit-btn" onclick="toggleEditForm(<?php echo e($todo->id); ?>)">Edit</button>
                                    <form action="<?php echo e(route('todos.destroy', $todo)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </span>
                            </li>
                            <!-- Inline Edit Form -->
                            <li class="list-group-item edit-form" id="edit-form-<?php echo e($todo->id); ?>" style="display: none;">
                                <form action="<?php echo e(route('todos.update', $todo)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="input-group">
                                        <input type="text" name="title" class="form-control" value="<?php echo e($todo->title); ?>" required>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-secondary" onclick="toggleEditForm(<?php echo e($todo->id); ?>)">Cancel</button>
                                    </div>
                                </form>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <li class="list-group-item">No todos in this group</li>
                        <?php endif; ?>
                    </ul>
                    <form action="<?php echo e(route('todos.store')); ?>" method="POST" class="mt-3">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="group_id" value="<?php echo e($group->id); ?>">
                        <div class="input-group">
                            <input type="text" name="title" class="form-control" placeholder="New Todo" required>
                            <button type="submit" class="btn btn-success">Add Todo</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>No groups created yet.</p>
        <?php endif; ?>
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
<?php /**PATH /home/laravel/todo_list/resources/views/todos/index.blade.php ENDPATH**/ ?>