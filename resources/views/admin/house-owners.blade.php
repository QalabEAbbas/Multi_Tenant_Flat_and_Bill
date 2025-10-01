<!DOCTYPE html>
<html>
<head>
    <title>House Owners</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>

<div class="container mt-5">
 <div class="d-flex justify-content-between mb-3">
<div class="d-flex justify-content-between mb-3">
    <h2>House Owners</h2>
    <div>
        <span class="badge bg-secondary">
            Logged in as: <strong id="currentUserName"></strong> (<span id="currentUserRole"></span>)
        </span>
    </div>
</div>
        <div>
            <a href="/buildings" class="btn btn-outline-primary btn-sm">🏢 Buildings</a>
            <a href="/flats" class="btn btn-outline-primary btn-sm">🚪 Flats</a>
            <a  class="btn btn-primary btn-sm">👤 Owners</a>
            <a href="/tenants" class="btn btn-outline-primary btn-sm">👥 Tenants</a>
        </div>
    </div>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addOwnerModal">+ Add Owner</button>

    <table class="table table-bordered" id="ownersTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- ADD OWNER MODAL -->
<div class="modal fade" id="addOwnerModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="addOwnerForm" class="modal-content needs-validation" novalidate>
            <div class="modal-header">
                <h5 class="modal-title">Add Owner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" id="add_name" class="form-control" required>
                    <div class="invalid-feedback">Name is required.</div>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" id="add_email" class="form-control" required>
                    <div class="invalid-feedback">Valid email required.</div>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" id="add_password" class="form-control" required>
                    <div class="invalid-feedback">Password is required.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Create Owner</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT OWNER MODAL -->
<div class="modal fade" id="editOwnerModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editOwnerForm" class="modal-content needs-validation" novalidate>
            <div class="modal-header">
                <h5 class="modal-title">Edit Owner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="edit_id">

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" id="edit_name" class="form-control" required>
                    <div class="invalid-feedback">Name is required.</div>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" id="edit_email" class="form-control" required>
                    <div class="invalid-feedback">Valid email required.</div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Update Owner</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('currentUserName').innerText = localStorage.getItem('user_name') || 'Guest';
document.getElementById('currentUserRole').innerText = localStorage.getItem('user_role') || 'N/A';

axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');

// SweetAlert Toast Instance
const Toast = Swal.mixin({
    toast: true,
    position: 'top-right',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true
});

function loadOwners() {
    axios.get('/api/owners').then(res => {
        const tbody = document.querySelector('#ownersTable tbody');
        tbody.innerHTML = '';
        res.data.data.forEach(owner => {
            tbody.innerHTML += `
                <tr>
                    <td>${owner.id}</td>
                    <td>${owner.name}</td>
                    <td>${owner.email}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="openEdit(${owner.id}, '${owner.name}', '${owner.email}')">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteOwner(${owner.id})">Delete</button>
                    </td>
                </tr>
            `;
        });
    });
}

document.getElementById('addOwnerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!this.checkValidity()) { this.classList.add('was-validated'); return; }

    axios.post('/api/owners', {
        name: add_name.value,
        email: add_email.value,
        password: add_password.value,
        role: 'house_owner'
    }).then(() => {
        loadOwners();
        document.querySelector('#addOwnerModal .btn-close').click();
        this.reset();
        Toast.fire({ icon: 'success', title: 'Owner Created!' });
    }).catch(err => {
        Swal.fire('Error!', err.response?.data?.message || 'Something went wrong', 'error');
    });
});

function openEdit(id, name, email) {
    edit_id.value = id;
    edit_name.value = name;
    edit_email.value = email;
    new bootstrap.Modal(document.getElementById('editOwnerModal')).show();
}

document.getElementById('editOwnerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!this.checkValidity()) { this.classList.add('was-validated'); return; }

    axios.put(`/api/owners/${edit_id.value}`, {
        name: edit_name.value,
        email: edit_email.value
    }).then(() => {
        loadOwners();
        document.querySelector('#editOwnerModal .btn-close').click();
        Toast.fire({ icon: 'success', title: 'Owner Updated!' });
    }).catch(err => {
        Swal.fire('Error!', err.response?.data?.message || 'Something went wrong', 'error');
    });
});

function deleteOwner(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if(result.isConfirmed) {
            axios.delete(`/api/owners/${id}`).then(() => {
                loadOwners();
                Toast.fire({ icon: 'success', title: 'Owner Deleted!' });
            }).catch(err => {
                Swal.fire('Error!', err.response?.data?.message || 'Something went wrong', 'error');
            });
        }
    });
}

loadOwners();
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
