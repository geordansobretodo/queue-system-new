<div class="container">
    <h2 class="mb-4">Queue Overview Report</h2>

    <div class="filter-container">
        <form method="post" action="">
            <label for="selectedDate">Filter by Date:</label>
            <div class="input-group">
                <input type="date" class="form-control" id="selectedDate" name="selectedDate" value="<?= isset($selectedDate) ? $selectedDate : ''; ?>" required>
                <div class="input-group-append">
                    <button class="btn btn-primary btn-filter" type="submit">Apply</button>
                </div>
            </div>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Metrics</th>
                <th>Values</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($selectedDate)) : ?>
                <tr>
                    <td><i class="fas fa-users metric-icon" style="color: black;"></i>Total Customers Served</td>
                    <td><?= $totalServed; ?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-user-friends metric-icon" style="color: black;"></i>Total Customers in Queue</td>
                    <td><?= $totalPending; ?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-chart-line metric-icon" style="color: black;"></i>Peak Hours</td>
                    <td><?= $peakHour; ?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-times-circle metric-icon" style="color: black;"></i>Missed Queues</td>
                    <td><?= $missedQueues; ?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-hourglass-half metric-icon" style="color: black;"></i>Average Serving Time (Regular Queue)</td>
                    <td><?= $averageServiceTimeRegular; ?></td>
                </tr>
                <tr>
                    <td><i class="fas fa-hourglass-half metric-icon" style="color: black;"></i>Average Serving Time (Priority Queue)</td>
                    <td><?= $averageServiceTimePriority;?></td>
                </tr>
            <?php else : ?>
                <tr>
                    <td colspan="2" class="text-center">Select a date to view metrics</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (!empty($selectedDate)) : ?>
        <div class="chart-container">
            <canvas id="overviewChart"></canvas>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Fetch data from PHP variables and use it in the JavaScript
    var totalServed = <?php echo $totalServed; ?>;
    var totalPending = <?php echo $totalPending; ?>;
    var missedQueues = <?php echo $missedQueues; ?>;

    // Dynamically update the chart data with PHP variables
    var chartData = {
        labels: ['Total Customers Served', 'Total Customers in Queue', 'Missed Queues'],
        datasets: [{
            label: 'Overview Metrics',
            data: [totalServed, totalPending, missedQueues],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    var ctx = document.getElementById('overviewChart').getContext('2d');
    var overviewChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>