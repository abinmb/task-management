<!DOCTYPE html>
<html>
<head>
    <title>Task Assigned</title>
</head>
<body>
    <h2>Hello, {{ $user->name }}!</h2>
    <p>A new task has been assigned to you:</p>
    <ul>
        <li><strong>Title:</strong> {{ $task->title }}</li>
        <li><strong>Description:</strong> {{ $task->description }}</li>
        <li><strong>Due Date:</strong> {{ $task->due_date ?? 'Not set' }}</li>
    </ul>
</body>
</html>
