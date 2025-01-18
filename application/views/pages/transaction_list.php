<div class="container mt-4">
    <h2 class="text-center mb-4 mt-3">Transaction Management</h2>
    <div class="row">
        <!-- First column -->
        <div class="col-md-6">
            <form id="customerForm" action="<?= base_url('Admin/add_option'); ?>">
                <div class="form-group">
                    <label for="typeOfCustomer"><i class="fas fa-user-tag"></i> Customer Type</label>
                    <input type="text" class="form-control" id="typeOfCustomer" name="option_name" placeholder="Enter customer type" required>
                    <input type="hidden" name="option_type" value="customer">
                    <button class="btn btn-primary mt-2" type="submit">Add Option</button>
                    <button class="btn btn-success mt-2" type="button" id="viewCustomerOptionsBtn">View Options</button>
                </div>
            </form>
            <form id="serviceForm" action="<?= base_url('Admin/add_option'); ?>">
                <div class="form-group">
                    <label for="typeOfService"><i class="fas fa-concierge-bell"></i> Service Type</label>
                    <input type="text" class="form-control" id="typeOfService" name="option_name" placeholder="Enter service type" required>
                    <input type="hidden" name="option_type" value="service">
                    <button class="btn btn-primary mt-2" type="submit">Add Option</button>
                    <button class="btn btn-success mt-2" type="button" id="viewServiceOptionsBtn">View Options</button>
                </div>
            </form>
        </div>

        <!-- Second column -->
        <div class="col-md-6">
            <form id="paymentForForm" action="<?= base_url('Admin/add_option'); ?>">
                <div class="form-group">
                    <label for="paymentFor"><i class="fas fa-money-bill-wave"></i> Payment For</label>
                    <input type="text" class="form-control" id="paymentFor" name="option_name" placeholder="Enter payment for" required>
                    <input type="hidden" name="option_type" value="payment_for">
                    <button class="btn btn-primary mt-2" type="submit">Add Option</button>
                    <button class="btn btn-success mt-2" type="button" id="viewPaymentOptionsBtn">View Options</button>
                </div>
            </form>
            <form id="paymentModeForm" action="<?= base_url('Admin/add_option'); ?>">
                <div class="form-group">
                    <label for="modeOfPayment"><i class="fas fa-credit-card"></i> Payment Mode</label>
                    <input type="text" class="form-control" id="modeOfPayment" name="option_name" placeholder="Enter payment mode" required>
                    <input type="hidden" name="option_type" value="payment_mode">
                    <button class="btn btn-primary mt-2" type="submit">Add Option</button>
                    <button class="btn btn-success mt-2" type="button" id="viewPaymentModeOptionsBtn">View Options</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals for viewing options -->
<?php foreach (['customer', 'service', 'payment_for', 'payment_mode'] as $type): ?>
    <div class="modal fade" id="<?= $type ?>OptionsModal" tabindex="-1" role="dialog" aria-labelledby="<?= $type ?>OptionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="<?= $type ?>OptionsModalLabel"><?= ucfirst(str_replace('_', ' ', $type)) ?> Options</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="<?= $type ?>OptionsList"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Delete COnfirmation Modal -->
<div class="modal fade" id="deleteoptionmodal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
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
                <button type="button" onclick="showOptionModal()" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" onclick="deleteOption()" class="btn btn-primary">Confirm</button>
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

<!-- Success Delete Modal -->
<div class="modal fade" id="successdeleteModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" onclick="$('#' + u_option + 'OptionsModal').modal('show');" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Success message will be injected here -->
            </div>
            <div class="modal-footer">
                        <button type="button" onclick="$('#' + u_option + 'OptionsModal').modal('show');"  class="btn btn-secondary" data-dismiss="modal">Close</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    let u_id = null;
    let u_option = null;

    function deleteOption() {
        if (u_id && u_option !== null) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('Admin/delete_option/'); ?>",
                data: {
                    id: u_id
                },
                success: function(response) {
                    if (response === 'success') {
                        $('#deleteoptionmodal').modal('hide');

                        $('#successdeleteModal .modal-body').text("Option deleted successfully!");
                        $('#successdeleteModal').modal('show');

                        loadOptions(u_option)
                    } else {
                        $('#deleteoptionmodal').modal('hide');

                        $('#errorModal .modal-body').text("Failed to delete option.");
                        $('#errorModal').modal('show');
                    }
                }
            });
        }
    }

    function loadOptions(optionType) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('Admin/get_options/'); ?>" + optionType,
            dataType: "json",
            success: function(response) {
                var optionsList = $("#" + optionType + "OptionsList");
                optionsList.empty();
                if (response.length > 0) {
                    $.each(response, function(index, option) {
                        optionsList.append("<li class='list-group-item d-flex justify-content-between align-items-center'>" + option.option_name + "<button class='btn btn-danger btn-sm' onclick='prepareDelete(" + option.id + ", \"" + optionType + "\")'>Delete</button></li>");
                    });
                } else {
                    optionsList.append("<li class='list-group-item'>No options available.</li>");
                }
            }
        });
    }

    $(document).ready(function() {
        $("#viewCustomerOptionsBtn").click(function() {
            loadOptions('customer');
            $("#customerOptionsModal").modal('show');
        });

        $("#viewServiceOptionsBtn").click(function() {
            loadOptions('service');
            $("#serviceOptionsModal").modal('show');
        });

        $("#viewPaymentOptionsBtn").click(function() {
            loadOptions('payment_for');
            $("#payment_forOptionsModal").modal('show');
        });

        $("#viewPaymentModeOptionsBtn").click(function() {
            loadOptions('payment_mode');
            $("#payment_modeOptionsModal").modal('show');
        });

        $("#customerForm, #serviceForm, #paymentForForm, #paymentModeForm").on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    $('#successModal .modal-body').text("Option added successfully!");
                    $('#successModal').modal('show');
                    form.trigger("reset");
                },
                error: function() {
                    $('#errorModal .modal-body').text("Failed to add opton.");
                    $('#errorModal').modal('show');
                }
            });
        });
    });

    function prepareDelete(optionId, optionType) {
        u_id = optionId;
        u_option = optionType;
        $('#deleteoptionmodal').modal('show');
        $('#' + optionType + 'OptionsModal').modal('hide');
    }

    function showOptionModal() {
        $('#' + u_option + 'OptionsModal').modal('show');
    }
</script>