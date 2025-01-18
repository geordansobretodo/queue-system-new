<div class="container">
    <h2 class="mb-4">Customer Details</h2>

    <div class="table-filter-container">
        <div style="margin-left: 60%; background-color: #f8f9fa;">
            <label for="selectedDate" style="background-color:  #f8f9fa;">Filter by Date:</label>
            <form id="dateFilterForm" method="post">
                <div class="input-group">
                <input type="date" class="form-control" id="startDate" name="startDate">
                <input type="date" class="form-control" id="endDate" name="endDate">
                <div class="input-group-append">
                        <button class="btn btn-primary btn-filter" type="button" onclick="applyDateFilter()">Apply</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table id="customerDetailsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Student Number</th>
                    <th>Service Type</th>
                    <th>Payment For</th>
                    <th>Payment Mode</th>
                    <th>Email</th>
                    <th>Queue Number</th>
                    <th>Queue Time</th>
                    <th>Status</th>
                    <th>Cashier Name</th>
                    <th>Average Service Time</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.0/js/dataTables.buttons.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        var dateText = $('selectedDate').val()
        var table = $('#customerDetailsTable').DataTable({
            columns: [{
                    data: 'name',
                    title: 'Name'
                },
                {
                    data: 'student_number',
                    title: 'Student Number'
                },
                {
                    data: 'service_type',
                    title: 'Service Type'
                },
                {
                    data: 'payment_for',
                    title: 'Payment For'
                },
                {
                    data: 'payment_mode',
                    title: 'Payment Mode'
                },
                {
                    data: 'email',
                    title: 'Email'
                },
                {
                    data: 'queue_number',
                    title: 'Queue Number'
                },
                {
                    data: 'queue_time',
                    title: 'Queue Time',
                    render: function(data) {
                        return moment(data).format('MMMM D, YYYY h:mm A');
                    }
                },
                {
                    data: 'status',
                    title: 'Status'
                },
                {
                    data: 'cashier_name',
                    title: 'Cashier Name'
                },
                {
                    data: 'average_service_time',
                    title: 'Average Service Time'
                }
            ],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [
                [7, 'asc']
            ],
            dom: 'lBftip',
            buttons: [
                'csv', 'excel',
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function(doc) {
                        doc.content.splice(0, 0, {
                            text: 'Customer Details Report',
                            fontSize: 16,
                            bold: true,
                            margin: [0, 0, 0, 20]
                        });

                        doc.content[1].layout = 'lightHorizontalLines';
                    }
                }
            ]
        });

        function applyDateFilter() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            if (!startDate || !endDate) {
                cashierTable.clear().draw();
                return;
            }

            $.ajax({
                url: '<?= base_url('Admin/filter_data'); ?>',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(data) {
                    table.clear().rows.add(JSON.parse(data)).draw();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", xhr.responseText);
                }
            });
        }

        $('#dateFilterForm .btn-filter').on('click', applyDateFilter);
    });
</script>

</body>

</html>