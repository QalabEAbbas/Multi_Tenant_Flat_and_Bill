<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container mt-5">
    <h2>Register</h2>
    <div id="message"></div>
    <form id="registerForm">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select class="form-control" name="role" required>
                <option value="admin">Admin</option>
                <option value="house_owner">House Owner</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <a href="/login" class="btn btn-link">Login</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('registerForm').addEventListener('submit', function(e){
    e.preventDefault();
    const form = e.target;
    axios.post('/api/register', {
        name: form.name.value,
        email: form.email.value,
        password: form.password.value,
        password_confirmation: form.password_confirmation.value,
        role: form.role.value
    })
    .then(res => {
        document.getElementById('message').innerHTML = `<div class="alert alert-success">${res.data.message}</div>`;
    })
    .catch(err => {
        const errors = err.response?.data?.errors || {};
        let msg = '';
        for (let key in errors) {
            msg += errors[key].join('<br>') + '<br>';
        }
        document.getElementById('message').innerHTML = `<div class="alert alert-danger">${msg}</div>`;
    });
});
</script>
</body>
</html>
