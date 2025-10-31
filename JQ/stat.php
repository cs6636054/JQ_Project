<?php
session_start();

include 'connect.php'; 

$sql = " SELECT r.name AS restaurant_name, DATE(q.booking_time) AS booking_date, SUM(q.number_of_people) AS total_diners
        FROM jq_queues q JOIN jq_restaurants r ON q.restaurant_id = r.restaurant_id
        WHERE q.status IN ('waiting', 'serving')  AND DATE(q.booking_time) BETWEEN '2025-09-01' AND '2025-10-31'
        GROUP BY r.name, booking_date
        ORDER BY booking_date ASC, r.name ASC; ";

$stmt = $pdo->query($sql);
$data = $stmt->fetchAll();

$report_data = [];
$all_dates = [];
$all_restaurants = [];

foreach ($data as $row) {
    $date = $row['booking_date'];
    $restaurant = $row['restaurant_name'];
    $diners = (int)$row['total_diners']; 
    
    $all_dates[$date] = true;
    $all_restaurants[$restaurant] = true;

    if (!isset($report_data[$date])) {
        $report_data[$date] = [];
    }
    $report_data[$date][$restaurant] = $diners;
}

$restaurant_names = array_keys($all_restaurants);
$dates = array_keys($all_dates);
sort($dates); 
sort($restaurant_names); 

//จัดโครงสร้างข้อมูลสำหรับ Chart.js (JSON Format)
$chart_datasets = [];

$background_colors = ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'];
$border_colors = ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'];

$i = 0;
foreach ($restaurant_names as $restaurant) {
    $data_points = [];
    foreach ($dates as $date) {
        $diners = $report_data[$date][$restaurant] ?? 0;
        $data_points[] = $diners;
    }

    $color_index = $i % count($background_colors);
    $chart_datasets[] = [
        'label' => htmlspecialchars($restaurant),
        'data' => $data_points,
        'backgroundColor' => $background_colors[$color_index], 
        'borderColor' => $border_colors[$color_index],
        'borderWidth' => 1,
    ];
    $i++;
}

// แปลงข้อมูลวันที่และชุดข้อมูลให้อยู่ในรูปแบบ JSON
$json_labels = json_encode(array_values($dates));
$json_datasets = json_encode($chart_datasets);

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style/stylemobile.css" media="screen and (max-width: 480px)">
    <link rel="stylesheet" href="style/styletablet.css" media="screen and (min-width: 481px)">
    <title>รายงานสถิติการจองคิว</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <style>      
        body {
            font-family: "Prompt", sans-serif;
            background-color: #fdf6f0;
            margin: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <main>
        <nav class="back" aria-label="Breadcrumb">
            <a href="admin/superadmin-homepage.php">back</a>
        </nav>
        
        <h1 class="report-title" >รายงานสรุปจำนวนผู้จองต่อร้านและต่อวัน</h1>

        <?php if (empty($data)): ?>
            <p style="text-align:center;">ไม่พบข้อมูลการจองในช่วงวันที่ที่กำหนด</p>
        <?php else: ?>
            
            <section class="chart-section" style="width: 80%; margin: 40px auto; background-color: white; border-radius: 30px; padding: 20px;">
                <h2>กราฟเปรียบเทียบจำนวนผู้จอง</h2>
                <canvas id="bookingChart"></canvas>
            </section>
            
            <section class="table-section">
                <h2 class="report-title">ตารางสรุปข้อมูล</h2>
                
                <table class="report-table" style="margin: 10 auto;">
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <?php foreach ($restaurant_names as $name): ?>
                                <th><?php echo htmlspecialchars($name); ?> (ผู้จอง)</th>
                            <?php endforeach; ?>
                            <th>รวมผู้จองทั้งหมดต่อวัน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $grand_total = 0;
                        $restaurant_totals = array_fill_keys($restaurant_names, 0); 

                        foreach ($dates as $date): 
                            $daily_total = 0;
                        ?>
                            <tr>
                                <td><?php echo $date; ?></td>
                                <?php foreach ($restaurant_names as $restaurant): 
                                    $diners = $report_data[$date][$restaurant] ?? 0;
                                ?>
                                    <td><?php echo $diners; ?></td>
                                    <?php 
                                    $daily_total += $diners;
                                    $restaurant_totals[$restaurant] += $diners;
                                    ?>
                                <?php endforeach; ?>
                                
                                <td><?php echo $daily_total; ?></td>
                            </tr>
                        <?php 
                        $grand_total += $daily_total;
                        endforeach; 
                        ?>
                        
                        </tbody>
                    <tfoot>
                        <tr>
                            <th>รวมผู้จองต่อร้าน (ตลอดช่วงเวลา)</th>
                            <?php foreach ($restaurant_names as $name): ?>
                                <th>
                                    <?php echo $restaurant_totals[$name]; ?>
                                </th>
                            <?php endforeach; ?>
                            <th>
                                <?php echo $grand_total; ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </section>
        <?php endif; ?>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const labels = <?php echo $json_labels; ?>;
        const datasets = <?php echo $json_datasets; ?>;

        const ctx = document.getElementById('bookingChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar', // กำหนดเป็นกราฟแท่ง
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'จำนวนผู้จองต่อร้านรายวัน (เปรียบเทียบระหว่างร้าน)'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'วันที่'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'จำนวนผู้จอง'
                        },
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) { if (value % 1 === 0) { return value; } }
                        }
                    }]
                }
            }
        });
    });
    </script>

</body>
</html>