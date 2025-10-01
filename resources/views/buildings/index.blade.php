<!DOCTYPE html>
<html>
<head>
    <title>Buildings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Buildings</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBuildingModal">Add Building</button>

    <table class="table table-bordered" id="buildingsTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addBuildingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addBuildingForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Building</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <input type="text" class="form-control" name="address">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editBuildingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editBuildingForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Building</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <input type="text" class="form-control" name="address">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');

function loadBuildings() {
    axios.get('/api/buildings').then(res => {
        let rows = '';
        res.data.forEach(b => {
            rows += `
                <tr>
                    <td>${b.id}</td>
                    <td>${b.name}</td>
                    <td>${b.address}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="openEditModal(${b.id}, '${b.name}', '${b.address}')">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteBuilding(${b.id})">Delete</button>
                    </td>
                </tr>`;
        });
        document.querySelector('#buildingsTable tbody').innerHTML = rows;
    });
}

function clearValidation(form) {
    form.querySelectorAll('.form-control').forEach(input => {
        input.classList.remove('is-invalid');
        input.nextElementSibling.innerHTML = '';
    });
}

document.getElementById('addBuildingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    clearValidation(form);
    axios.post('/api/buildings', {
        name: form.name.value,
        address: form.address.value
    }).then(res => {
        form.reset();
        bootstrap.Modal.getInstance(document.getElementById('addBuildingModal')).hide();
        loadBuildings();
    }).catch(err => {
        if (err.response && err.response.data.errors) {
            for (let field in err.response.data.errors) {
                const input = form.querySelector(`[name="${field}"]`);
                input.classList.add('is-invalid');
                input.nextElementSibling.innerHTML = err.response.data.errors[field][0];
            }
        }
    });
});

function openEditModal(id, name, address) {
    const modal = document.getElementById('editBuildingModal');
    modal.querySelector('[name="id"]').value = id;
    modal.querySelector('[name="name"]').value = name;
    modal.querySelector('[name="address"]').value = address;
    new bootstrap.Modal(modal).show();
}

document.getElementById('editBuildingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    clearValidation(form);
    const id = form.id.value;
    axios.put(`/api/buildings/${id}`, {
        name: form.name.value,
        address: form.address.value
    }).then(res => {
        bootstrap.Modal.getInstance(document.getElementById('editBuildingModal')).hide();
        loadBuildings();
    }).catch(err => {
        if (err.response && err.response.data.errors) {
            for (let field in err.response.data.errors) {
                const input = form.querySelector(`[name="${field}"]`);
                input.classList.add('is-invalid');
                input.nextElementSibling.innerHTML = err.response.data.errors[field][0];
            }
        }
    });
});

function deleteBuilding(id) {
    if (!confirm('Are you sure?')) return;
    axios.delete(`/api/buildings/${id}`).then(res => {
        loadBuildings();
    });
}

loadBuildings();
</script>
</body>
</html>
