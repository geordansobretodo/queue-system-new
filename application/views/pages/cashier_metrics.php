<div class="container">
    <h2 class="mb-4">Cashier Metrics</h2>

    <div class="table-filter-container">
        <div style="margin-left: 65%; background-color: #f8f9fa;">
            <label for="startDate" style="background-color:  #f8f9fa;">Filter by Date Range:</label>
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

    <table id="cashierDetailsTable" class="table table-hover table-bordered">
        <thead>
            <tr class="alert-secondary">
                <th>Metric</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <br><br>

    <div class="table-responsive">
        <table id="customerDetailsTable" class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>Cashier Name</th>
                    <th>Total Transactions</th>
                    <th>Total Completed</th>
                    <th>Total No Shows</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/2.3.0/js/buttons.html5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    $(document).ready(function() {
        var customerTable = $('#customerDetailsTable').DataTable({
            columns: [{
                    data: 'name',
                    title: 'Cashier Name'
                },
                {
                    data: 'total_transactions',
                    title: 'Total Transactions'
                },
                {
                    data: 'total_completed',
                    title: 'Total Completed'
                },
                {
                    data: 'total_noshows',
                    title: 'Total No Shows'
                },
                {
                    data: 'average_service_time',
                    title: 'Average Service Time'
                }
            ],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [
                [0, 'asc']
            ],
            dom: '1Bfrtip',
            buttons: [{
                    text: 'CSV',
                    action: function(e, dt, node, config) {
                        exportAllData('csv');
                    }
                },
                {
                    text: 'Excel',
                    action: function(e, dt, node, config) {
                        exportAllData('excel');
                    }
                },
                {
                    text: 'PDF',
                    action: function(e, dt, node, config) {
                        exportAllData('pdf');
                    }
                }
            ]
        });

        var cashierTable = $('#cashierDetailsTable').DataTable({
            columns: [{
                    data: 'metric',
                    title: 'Metrics'
                },
                {
                    data: 'value',
                    title: 'Values'
                }
            ],
            paging: false,
            searching: false,
            info: false,
            order: [
                [0, 'asc']
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
                url: '<?= base_url('Admin/filter_cashier_data'); ?>',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(data) {
                    var cashierFormattedData = [{
                            metric: '<i class="fa fa-hourglass-half metric-icon" style="color: black;"></i>Overall Average Service Time',
                            value: data.cashierData.average_service_time
                        },
                        {
                            metric: '<i class="fa fa-times-circle metric-icon" style="color: black;"></i>Total No Shows',
                            value: data.cashierData.total_noshows
                        },
                        {
                            metric: '<i class="fa fa-check-circle metric-icon" style="color: black;"></i>Total Completed',
                            value: data.cashierData.total_completed
                        },
                        {
                            metric: '<i class="fa fa-users metric-icon" style="color: black;"></i>Total Transactions',
                            value: data.cashierData.total_transactions
                        }
                    ];
                    cashierTable.clear().rows.add(cashierFormattedData).draw();

                    var customerDetailsData = data.customerData.map(function(val) {
                        return {
                            name: val.name,
                            total_transactions: val.total_transactions,
                            total_completed: val.total_completed,
                            total_noshows: val.total_noshows,
                            average_service_time: val.average_service_time
                        };
                    });
                    customerTable.clear().rows.add(customerDetailsData).draw();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", xhr.responseText);
                }
            });
        }
        $('#dateFilterForm .btn-filter').on('click', applyDateFilter);

        function exportAllData(format) {
            var customerData = customerTable.rows().data().toArray();
            var cashierData = cashierTable.rows().data().toArray();

            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            var dateRangeText = (startDate && endDate) ? `From ${startDate} to ${endDate}` : '';

            if (format === 'pdf') {
                var docDefinition = {
                    content: [{
                            text: `Cashier Metrics`,
                            style: 'header'
                        },
                        {
                            text: dateRangeText,
                            style: 'subheader'
                        },
                        {
                            table: {
                                body: [
                                    ['Metrics', 'Values'],
                                    ...cashierData.map(row => [row.metric.replace(/<i.*?>.*?<\/i>/g, ''), row.value])
                                ]
                            }
                        },
                        {
                            text: 'Cashier Transaction Details',
                            style: 'header'
                        },
                        {
                            table: {
                                body: [
                                    ['Cashier Name', 'Total Transactions', 'Total Completed', 'Total No Shows', 'Average Service Time'],
                                    ...customerData.map(row => [row.name, row.total_transactions, row.total_completed, row.total_noshows, row.average_service_time])
                                ]
                            }
                        }
                    ],
                    styles: {
                        header: {
                            fontSize: 18,
                            bold: true,
                            margin: [0, 0, 0, 10]
                        }
                    }
                };

                pdfMake.createPdf(docDefinition).download('Cashier_Details.pdf');
            }

            if (format === 'csv') {
                var combinedTableData = [];

                combinedTableData.push(['Metrics', 'Values']);
                cashierData.forEach(function(row) {
                    var metricText = row.metric.replace(/<i.*?>.*?<\/i>/g, '');
                    combinedTableData.push([metricText, row.value]);
                });

                combinedTableData.push([]);
                combinedTableData.push(['Cashier Name', 'Total Transactions', 'Total Completed', 'Total No Shows', 'Average Service Time']);
                customerData.forEach(function(row) {
                    combinedTableData.push([row.name, row.total_transactions, row.total_completed, row.total_noshows, row.average_service_time]);
                });

                var csvContent = "data:text/csv;charset=utf-8,";
                combinedTableData.forEach(function(rowArray) {
                    var row = rowArray.join(",");
                    csvContent += row + "\r\n";
                });

                var encodedUri = encodeURI(csvContent);
                var link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "Cashier_Details.csv");
                document.body.appendChild(link);
                link.click();
            }

            if (format === 'excel') {
                var wb = XLSX.utils.book_new();

                var cashierMetricsData = [
                    ['Metrics', 'Values'],
                    ...cashierData.map(row => [row.metric.replace(/<i.*?>.*?<\/i>/g, ''), row.value])
                ];
                var ws1 = XLSX.utils.aoa_to_sheet(cashierMetricsData);
                XLSX.utils.book_append_sheet(wb, ws1, 'Cashier Metrics');

                var customerDetailsData = [
                    ['Cashier Name', 'Total Transactions', 'Total Completed', 'Total No Shows', 'Average Service Time'],
                    ...customerData.map(row => [row.name, row.total_transactions, row.total_completed, row.total_noshows, row.average_service_time])
                ];
                var ws2 = XLSX.utils.aoa_to_sheet(customerDetailsData);
                XLSX.utils.book_append_sheet(wb, ws2, 'Customer Details');

                XLSX.writeFile(wb, 'Cashier_Details.xlsx');
            }
        }

    });
</script>

</body>

</html>