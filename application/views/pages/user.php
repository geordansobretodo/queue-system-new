<div class="container mt-4">
    <form class="d-flex justify-content-between align-items-center mb-3">
        <h2>User Management</h2>
        <button type="button" class="btn btn-primary" id="addUserButton"><i class="fa fa-plus"></i> Add New</button>
    </form>

    <div class="form-group mb-3">
        <input type="text" id="search" class="form-control" placeholder="Search" autocomplete="off">
    </div>

    <table class="table table-striped table-bordered table-hover" id="userTable">
        <thead class="thead-secondary">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <nav aria-label="Page navigation" style="position: fixed; bottom: 0; left: 0; width: 100%; background-color: #f8f9fa; z-index: 1000;">
        <ul class="pagination justify-content-center" id="pagination"></ul>
    </nav>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editName">Manage User:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="post" action="<?= base_url('Admin/update_user'); ?>" onsubmit="return confirm('Are you sure you want to save changes?')">
                    <input type="hidden" id="editUserId" name="editUserId">
                    <div class="form-group">
                        <label for="editUsername"><i class="fa fa-user"></i> Username:</label>
                        <input type="text" id="editUsername" name="editUsername" class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="editPasswordToggle">
                            <label for="editPassword"><i class="fa fa-lock"></i> Password:</label>
                        </div>
                        <input type="password" id="editPassword" name="editPassword" class="form-control" placeholder="Leave blank to keep current password" disabled>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button onclick="hideEditUserModal()" id="editsubmitBtn" type="button" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel"><i class="fa fa-plus"></i> Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="post" action="<?= base_url('Admin/add_user'); ?>">
                    <div class="form-group">
                        <label for="newName"><i class="fa fa-user"></i> Name:</label>
                        <input type="text" id="newName" name="newName" class="form-control" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group">
                        <label for="newUsername"><i class="fa fa-user"></i> Username:</label>
                        <input type="text" id="newUsername" name="newUsername" class="form-control" required placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                        <label for="newPassword"><i class="fa fa-lock   "></i> Password:</label>
                        <input type="password" id="newPassword" name="newPassword" class="form-control" required placeholder="Enter Password">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick="hideAddUserModal()" class="btn btn-primary" id=submitBtn>Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel"><i class="fa fa-exclamation-triangle"></i> Confirm Submission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to add this user?
            </div>
            <div class="modal-footer">
                <button type="button" onclick="showAddUserModal()" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmSubmit" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Confirmation Modal -->
<div class="modal fade" id="editconfirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel"><i class="fa fa-exclamation-triangle"></i> Confirm Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to update this user?
            </div>
            <div class="modal-footer">
                <button type="button" onclick="showEditUserModal()" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="editconfirmSubmit" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel"><i class="fa fa-exclamation-triangle"></i> Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" onclick="confirmDelete()" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Success message will be injected here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Error message will be injected here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    let u_id = null;

    function fetchTableData(page = 1, search = '') {
        $.ajax({
            url: '<?= base_url('Admin/fetch_user_data') ?>',
            method: 'GET',
            data: {
                page: page,
                search: search
            },
            dataType: 'json',
            success: function(response) {
                let rows = '';
                $.each(response.users, function(index, user) {
                    rows += `<tr>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.username}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> Edit
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-toggle="modal" onclick="editUser(${user.id}, '${user.name}', '${user.username}')"><i class="fa fa-user"></i> Manage User</a>
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="prepareDelete(${user.id})"><i class="fa fa-trash"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>`;
                });
                $('#userTable tbody').html(rows);

                let pagination = '';
                if (page == 1) {
                    pagination += `<li class="page-item disabled"><a class="page-link" href="#")">&laquo</a></li>`;
                }
                if (page > 1) {
                    pagination += `<li class="page-item"><a class="page-link" href="#" onclick="fetchTableData(${page - 1}, '${search}')">&laquo</a></li>`;
                }
                for (let i = 1; i <= response.total_pages; i++) {
                    pagination += `<li class="page-item ${i === page ? 'active' : ''}">
                                <a class="page-link" href="#" onclick="fetchTableData(${i}, '${search}')">${i}</a>
                            </li>`;
                }
                if (page < response.total_pages) {
                    pagination += `<li class="page-item"><a class="page-link" href="#" onclick="fetchTableData(${page + 1}, '${search}')">&raquo</a></li>`;
                }
                if (page == response.total_pages) {
                    pagination += `<li class="page-item disabled"><a class="page-link" href="#")">&raquo</a></li>`;
                }
                $('#pagination').html(pagination);
            }
        });
    }

    $(document).ready(function() {
        fetchTableData();

        $('#search').on('keyup', function() {
            fetchTableData(1, $(this).val());
        });
    });

    function showAddUserModal() {
        $('#addUserModal').modal('show');
    }

    function hideAddUserModal() {
        $('#addUserModal').modal('hide');
    }

    function hideEditUserModal() {
        $('#editUserModal').modal('hide');
    }


    document.getElementById('addUserButton').addEventListener('click', showAddUserModal);

    function editUser(id, name, username) {
        $("#editUserId").val(id);
        $("#editName").html('<i class="fa fa-user"></i> Manage User: ' + name);
        $("#editUsername").val(username);
        $("#editUserModal").modal("show");
    }

    // password toggle checkbox
    $('#editPasswordToggle').on('change', function() {
        if ($(this).is(':checked')) {
            $('#editPassword').prop('disabled', false);
        } else {
            $('#editPassword').prop('disabled', true);
        }
    });

    function showEditUserModal() {
        $("#editUserModal").modal("show");
    }

    function prepareDelete(id) {
        u_id = id;
        $('#deleteUserModal').modal('show');
    }

    function confirmDelete() {
        if (u_id !== null) {
            window.location.href = '<?= base_url('Admin/delete/'); ?>' + u_id;
        }
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addUserForm');
    const submitBtn = document.getElementById('submitBtn');
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const confirmSubmit = document.getElementById('confirmSubmit');

    submitBtn.addEventListener('click', function() {
        // Show the confirmation modal
        confirmModal.show();
    });

    confirmSubmit.addEventListener('click', function() {
        // Hide the confirmation modal
        confirmModal.hide();
        // Submit the form
        form.submit();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editUserForm');
    const submitBtn = document.getElementById('editsubmitBtn');
    const confirmModal = new bootstrap.Modal(document.getElementById('editconfirmModal'));
    const confirmSubmit = document.getElementById('editconfirmSubmit');

    submitBtn.addEventListener('click', function() {
        // Show the confirmation modal
        confirmModal.show();
    });

    confirmSubmit.addEventListener('click', function() {
        // Hide the confirmation modal
        confirmModal.hide();
        // Submit the form
        form.submit();
    });
});
</script>

<?php if ($this->session->flashdata('success')): ?>
    <script>
        $(document).ready(function() {
            $('#successModal .modal-body').text("<?php echo $this->session->flashdata('success'); ?>");
            $('#successModal').modal('show');
        });
    </script>
<?php elseif ($this->session->flashdata('error')): ?>
    <script>
        $(document).ready(function() {
            $('#errorModal .modal-body').text("<?php echo $this->session->flashdata('error'); ?>");
            $('#errorModal').modal('show');
        });
    </script>
<?php endif; ?>

</body>

</html>