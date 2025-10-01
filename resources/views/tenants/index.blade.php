<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Tenants Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-3">Tenants</h3>

    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTenantModal">+ Add Tenant</button>
        <button class="btn btn-secondary" onclick="loadTenants()">Refresh</button>
    </div>

    <div id="globalAlert"></div>

    <table class="table table-bordered" id="tenantsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Flat</th>
                <th>Building</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- ADD TENANT MODAL -->
<div class="modal fade" id="addTenantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="addTenantForm" class="modal-content needs-validation" novalidate>
      <div class="modal-header">
        <h5 class="modal-title">Add Tenant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
            <label class="form-label">Name *</label>
            <input type="text" name="name" id="add_name" class="form-control">
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" id="add_email" class="form-control">
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact *</label>
            <input type="text" name="contact" id="add_contact" class="form-control">
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Assign Flat *</label>
            <select name="flat_id" id="add_flat_id" class="form-select">
                <option value="">Loading available flats...</option>
            </select>
            <div class="invalid-feedback"></div>
            <small class="form-text text-muted">Only flats without tenants are shown.</small>
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Create Tenant</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT TENANT MODAL -->
<div class="modal fade" id="editTenantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editTenantForm" class="modal-content needs-validation" novalidate>
      <div class="modal-header">
        <h5 class="modal-title">Edit Tenant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <input type="hidden" id="edit_tenant_id">

        <div class="mb-3">
            <label class="form-label">Name *</label>
            <input type="text" id="edit_name" class="form-control">
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" id="edit_email" class="form-control">
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact *</label>
            <input type="text" id="edit_contact" class="form-control">
            <div class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Assign Flat *</label>
            <select id="edit_flat_id" class="form-select">
                <option value="">Loading flats...</option>
            </select>
            <div class="invalid-feedback"></div>
            <small class="form-text text-muted">Shows available (empty) flats plus the tenant's current flat (preselected).</small>
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-success">Update Tenant</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* ---------- CONFIG ---------- */
axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');

/* SweetAlert2 toast instance */
const Toast = Swal.mixin({
    toast: true,
    position: 'top-right',
    showConfirmButton: false,
    timer: 2200,
    timerProgressBar: true
});

/* Utility to clear validation of a form */
function clearFormValidation(formEl) {
    formEl.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    formEl.querySelectorAll('.invalid-feedback').forEach(fe => fe.textContent = '');
}

/* Utility: show server validation errors (errors = err.response.data.errors)*/
function showValidationErrors(formEl, errors) {
    if (!errors) return;
    for (const field in errors) {
        const input = formEl.querySelector(`[name="${field}"]`) || formEl.querySelector(`#${field}`); // fallback by id
        if (input) {
            input.classList.add('is-invalid');
            const fb = input.nextElementSibling;
            if (fb && fb.classList.contains('invalid-feedback')) {
                fb.textContent = errors[field][0];
            }
        }
    }
}

/* ---------- LOAD DATA ---------- */

/**
 * Load tenants into table
 */
function loadTenants() {
    axios.get('/api/tenants')
    .then(res => {
        const tbody = document.querySelector('#tenantsTable tbody');
        let html = '';
        (res.data || []).forEach(t => {
            html += `<tr>
                <td>${t.id}</td>
                <td>${escapeHtml(t.name)}</td>
                <td>${escapeHtml(t.email || '')}</td>
                <td>${escapeHtml(t.contact || '')}</td>
                <td>${t.flat ? escapeHtml(t.flat.flat_number) : '-'}</td>
                <td>${t.flat && t.flat.building ? escapeHtml(t.flat.building.name) : '-'}</td>
                <td>${t.creator ? escapeHtml(t.creator.name) : '-'}</td>
                <td>
                    <button class="btn btn-sm btn-warning me-1" onclick="openEditTenant(${t.id})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="confirmDeleteTenant(${t.id})">Delete</button>
                </td>
            </tr>`;
        });
        tbody.innerHTML = html;
    })
    .catch(err => {
        console.error(err);
        document.getElementById('globalAlert').innerHTML = `<div class="alert alert-danger">Failed to load tenants.</div>`;
    });
}

/**
 * Load available flats grouped by building for Add modal
 * only flats with tenant_id == null
 */
function loadFlatsForAdd() {
    const sel = document.getElementById('add_flat_id');
    sel.innerHTML = '<option value="">Loading available flats...</option>';

    axios.get('/api/buildings') // expecting each building -> includes flats
    .then(res => {
        sel.innerHTML = '<option value="">Select flat</option>';
        const buildings = res.data || [];
        buildings.forEach(b => {
            // collect empty flats
            const emptyFlats = (b.flats || []).filter(f => !f.tenant_id);
            if (emptyFlats.length === 0) return;
            const optgroup = document.createElement('optgroup');
            optgroup.label = b.name;
            emptyFlats.forEach(f => {
                const opt = document.createElement('option');
                opt.value = f.id;
                opt.textContent = `${f.flat_number} (${f.owner_name || 'Owner N/A'})`;
                optgroup.appendChild(opt);
            });
            sel.appendChild(optgroup);
        });
    })
    .catch(err => {
        console.error(err);
        sel.innerHTML = '<option value="">Failed to load flats</option>';
    });
}

/**
 * Load flats for Edit modal:
 * - include all empty flats
 * - include the tenant's current flat even if occupied (pre-select it)
 */
function loadFlatsForEdit(currentFlatId = null) {
    const sel = document.getElementById('edit_flat_id');
    sel.innerHTML = '<option value="">Loading flats...</option>';

    axios.get('/api/buildings')
    .then(res => {
        sel.innerHTML = '<option value="">Select flat</option>';
        const buildings = res.data || [];

        buildings.forEach(b => {
            // flats that are empty OR is the current flat
            const flatsToShow = (b.flats || []).filter(f =>
                (!f.tenant_id) || (currentFlatId && f.id == currentFlatId)
            );

            if (flatsToShow.length === 0) return;

            const optgroup = document.createElement('optgroup');
            optgroup.label = b.name;

            flatsToShow.forEach(f => {
                const opt = document.createElement('option');
                opt.value = f.id;
                opt.textContent = `${f.flat_number} ${f.tenant_id ? '(occupied)' : ''}`;
                optgroup.appendChild(opt);
            });

            sel.appendChild(optgroup);
        });
    })
    .catch(err => {
        console.error(err);
        sel.innerHTML = '<option value="">Failed to load flats</option>';
    });
}

/* ---------- ADD TENANT ---------- */

document.getElementById('addTenantForm').addEventListener('submit', function(e){
    e.preventDefault();
    const form = this;
    clearFormValidation(form);

    // simple front validation
    let hasErr = false;
    if (!add_name.value.trim()) { markInvalid(add_name, 'Name is required'); hasErr = true; }
    if (!add_email.value.trim()) { markInvalid(add_email, 'Email is required'); hasErr = true; }
    if (!add_contact.value.trim()) { markInvalid(add_contact, 'Contact is required'); hasErr = true; }
    if (!add_flat_id.value) { markInvalid(add_flat_id, 'Please select a flat'); hasErr = true; }
    if (hasErr) return;

    axios.post('/api/tenants', {
        name: add_name.value,
        email: add_email.value,
        contact: add_contact.value,
        flat_id: add_flat_id.value
    }).then(res => {
        bootstrap.Modal.getInstance(document.getElementById('addTenantModal')).hide();
        form.reset();
        Toast.fire({ icon: 'success', title: 'Tenant created' });
        loadTenants();
        loadFlatsForAdd(); // refresh available flats after creating tenant
    }).catch(err => {
        if (err.response?.data?.errors) {
            showValidationErrors(form, err.response.data.errors);
        } else {
            Swal.fire('Error', err.response?.data?.message || 'Failed to create tenant', 'error');
        }
    });
});

/* ---------- EDIT TENANT ---------- */

/**
 * Open Edit modal — fetch tenant detail and populate fields + flats (including current)
 */
function openEditTenant(tenantId) {
    clearFormValidation(document.getElementById('editTenantForm'));
    // fetch tenant
    axios.get(`/api/tenants/${tenantId}`)
    .then(res => {
        const t = res.data;
        document.getElementById('edit_tenant_id').value = t.id;
        document.getElementById('edit_name').value = t.name || '';
        document.getElementById('edit_email').value = t.email || '';
        document.getElementById('edit_contact').value = t.contact || '';

        // Load flats for edit (include current flat even if occupied)
        loadFlatsForEdit(t.flat_id);

        // small delay to ensure select options created, then set selected
        setTimeout(() => {
            if (t.flat_id) {
                const sel = document.getElementById('edit_flat_id');
                sel.value = t.flat_id;
            }
        }, 250);

        new bootstrap.Modal(document.getElementById('editTenantModal')).show();
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Failed to load tenant details', 'error');
    });
}

document.getElementById('editTenantForm').addEventListener('submit', function(e){
    e.preventDefault();
    const form = this;
    clearFormValidation(form);

    let hasErr = false;
    if (!edit_name.value.trim()) { markInvalid(edit_name, 'Name is required'); hasErr = true; }
    if (!edit_email.value.trim()) { markInvalid(edit_email, 'Email is required'); hasErr = true; }
    if (!edit_contact.value.trim()) { markInvalid(edit_contact, 'Contact is required'); hasErr = true; }
    if (!edit_flat_id.value) { markInvalid(edit_flat_id, 'Please select a flat'); hasErr = true; }
    if (hasErr) return;

    axios.put(`/api/tenants/${edit_tenant_id.value}`, {
        name: edit_name.value,
        email: edit_email.value,
        contact: edit_contact.value,
        flat_id: edit_flat_id.value
    }).then(res => {
        bootstrap.Modal.getInstance(document.getElementById('editTenantModal')).hide();
        Toast.fire({ icon: 'success', title: 'Tenant updated' });
        loadTenants();
        loadFlatsForAdd(); // refresh add list
    }).catch(err => {
        if (err.response?.data?.errors) {
            showValidationErrors(form, err.response.data.errors);
        } else {
            Swal.fire('Error', err.response?.data?.message || 'Failed to update tenant', 'error');
        }
    });
});

/* ---------- DELETE TENANT ---------- */

function confirmDeleteTenant(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Deleting a tenant will unassign them from the flat.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel'
    }).then(result => {
        if (result.isConfirmed) {
            axios.delete(`/api/tenants/${id}`)
            .then(() => {
                Toast.fire({ icon: 'success', title: 'Tenant deleted' });
                loadTenants();
                loadFlatsForAdd();
            })
            .catch(err => {
                Swal.fire('Error', err.response?.data?.message || 'Delete failed', 'error');
            });
        }
    });
}

/* ---------- Helpers ---------- */

function markInvalid(el, msg) {
    el.classList.add('is-invalid');
    const fb = el.nextElementSibling;
    if (fb && fb.classList.contains('invalid-feedback')) fb.textContent = msg;
}

/* Escape HTML to avoid XSS when putting names into table */
function escapeHtml(unsafe) {
    if (!unsafe && unsafe !== 0) return '';
    return String(unsafe)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

/* Initial load */
loadTenants();
loadFlatsForAdd();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
