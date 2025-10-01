<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container mt-5">
    <h2>Login</h2>
    <div id="message"></div>
    <form id="loginForm">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3 position-relative">
            <label>Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    Show
                </button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="/register" class="btn btn-link">Register</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', function(e){
    e.preventDefault();
    const form = e.target;
    const email = form.email.value.trim();
    const password = form.password.value.trim();

    // ✅ Basic validation
    if (!email) {
        document.getElementById('message').innerHTML =
            `<div class="alert alert-warning">Please enter your email.</div>`;
        return;
    }
    if (!/\S+@\S+\.\S+/.test(email)) { // simple email regex
        document.getElementById('message').innerHTML =
            `<div class="alert alert-warning">Please enter a valid email address.</div>`;
        return;
    }
    if (!password) {
        document.getElementById('message').innerHTML =
            `<div class="alert alert-warning">Please enter your password.</div>`;
        return;
    }

    // ✅ API call only if valid
    axios.post('/api/login', { email, password })
    .then(res => {
        localStorage.setItem('token', res.data.token);
        localStorage.setItem('user_name', res.data.user?.name ?? 'Unknown');
        localStorage.setItem('user_role', res.data.user?.role ?? res.data.user?.roles?.[0] ?? 'N/A');

        const role = res.data.user?.role;
        if (role === 'admin') {
            window.location.href = '/admin/house-owners';
        } else if (role === 'house_owner') {
            // Redirect house owners to the index page (buildings list)
            window.location.href = '/admin/house-owners';
        } else {
            // Fallback: send to buildings index as a safe default
            window.location.href = '/admin/house-owners';
        }
    })
    .catch(err => {
        document.getElementById('message').innerHTML = `
            <div class="alert alert-danger">
                ${err.response?.data?.message || 'Login failed'}
            </div>`;
    });
});

document.getElementById('togglePassword').addEventListener('click', function(){
    const passwordField = document.getElementById('password');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    this.textContent = type === 'password' ? 'Show' : 'Hide';
});
</script>

</body>
</html>
