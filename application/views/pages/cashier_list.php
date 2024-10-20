<div class="container mt-4">
    <form class="d-flex justify-content-between align-items-center mb-3">
        <h2>Cashier List</h2>
        <button type="button" class="btn btn-primary" id="addUserButton"><i class="fa fa-plus"></i> Add New</button>
    </form>

    <div class="form-group mb-3">
        <input type="text" id="search" class="form-control" placeholder="Search" autocomplete="off">
    </div>

    <table class="table table-striped table-bordered table-hover" id="cashierTable">
        <thead class="thead-secondary">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <nav aria-label="Page navigation" style="position: fixed; bottom: 0; left: 0; width: 100%; background-color: #f8f9fa; z-index: 1000;">
        <ul class="pagination justify-content-center" id="pagination"></ul>
    </nav>
</div>

<!-- Edit Cashier Modal -->
<div class="modal fade" id="editCashierModal" tabindex="-1" role="dialog" aria-labelledby="editCashierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCashierModalLabel">Edit Cashier Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCashierForm" method="post" action="<?= base_url('Admin/update_cashier'); ?>">
                    <input type="hidden" id="editCashierId" name="editCashierId">
                    <div class="form-group">
                        <label for="editName">Name:</label>
                        <input type="text" id="editName" name="editName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editPassword">Password:</label>
                        <input type="password" id="editPassword" name="editPassword" class="form-control" placeholder="Leave blank to keep current password">
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status:</label>
                        <select id="editStatus" name="editStatus" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" onclick="hideEditCashierModal()" id="editsubmitBtn" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addCashierModal" tabindex="-1" role="dialog" aria-labelledby="addCashierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCashierModalLabel">Add New Cashier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="addCashierForm" action="<?= base_url('Admin/add_cashier'); ?>">
                    <div class="form-group">
                        <label for="newName">Name:</label>
                        <input type="text" id="newName" name="newName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">Password:</label>
                        <input type="password" id="newPassword" name="newPassword" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="newStatus">Status:</label>
                        <select id="newStatus" name="newStatus" class="form-control" required disabled>
                            <option value="Active" selected>Active</option>
                        </select>
                        <!-- Hidden input to submit the value -->
                        <input type="hidden" name="newStatus" value="Inactive">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" onclick="hideaddCashierModal()" id="submitBtn" class="btn btn-primary">Save</button>
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
                <button type="button" onclick="showaddCashierModal()" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                <button type="button" onclick="showEditCashierModal()" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="editconfirmSubmit" class="btn btn-primary">Confirm</button>
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
    function fetchTableData(page = 1, search = '') {
        $.ajax({
            url: '<?= base_url('Admin/fetch_cashier_data') ?>',
            method: 'GET',
            data: {
                page: page,
                search: search
            },
            dataType: 'json',
            success: function(response) {
                let rows = '';
                $.each(response.cashiers, function(index, cashier) {
                    rows += `<tr>
                            <td>${cashier.id}</td>
                            <td>${cashier.name}</td>
                            <td>${cashier.status.charAt(0).toUpperCase() + cashier.status.slice(1).toLowerCase()}</td>
                            <td>
                                <button type="button" class="btn btn-info mr-2" onclick="editCashier(${cashier.id}, '${cashier.name}', '${cashier.status}')"><i class="fa fa-cogs"></i> Edit</button>
                            </td>
                        </tr>`;
                });
                $('#cashierTable tbody').html(rows);

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

    function showaddCashierModal() {
        $('#addCashierModal').modal('show');
    }

    function hideaddCashierModal() {
        $('#addCashierModal').modal('hide');
    }

    function hideEditCashierModal() {
        $('#editCashierModal').modal('hide');
    }

    document.getElementById('addUserButton').addEventListener('click', showaddCashierModal);

    function editCashier(id, name, status) {
        $("#editCashierId").val(id);
        $("#editName").val(name);
        $("#editStatus").val(status);
        $("#editCashierModal").modal("show");
    }

    function showEditCashierModal() {
        $("#editCashierModal").modal("show");
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addCashierForm');
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
    const form = document.getElementById('editCashierForm');
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