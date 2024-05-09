<!DOCTYPE html>
<html>
<head>
    <title>New User Registration Notification</title>
</head>
<body>
    <p>A new user has registered on the platform:</p>
    <ul>
        <li>Name: {{ $user->name }}</li>
        <li>Email: {{ $user->email }}</li>
    </ul>
    <p>Thank you.</p>
</body>
</html>
