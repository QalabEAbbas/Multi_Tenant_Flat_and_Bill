<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <p>Welcome, {{ auth()->user()->name }}</p>
    <a href="/admin/house-owners" class="btn btn-primary">Manage House Owners</a>
    <a href="/buildings" class="btn btn-secondary">Manage Buildings</a>
    <a href="/flats" class="btn btn-success">Manage Flats</a>
    <button class="btn btn-danger" onclick="logout()">Logout</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');

function logout() {
    axios.post('/api/logout')
    .then(res => {
        localStorage.removeItem('token');
        window.location.href = '/login';
    });
}
</script>
</body>
</html>
