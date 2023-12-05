<?php
session_start();
function main()
{
    include("../../../mod/database_connection.php");

    ?>

    <div class="container-fluid">

        <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Earnings (Month)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    $sql_earn_month = "SELECT SUM(total_amount) AS total_revenue
                                        FROM orders
                                        WHERE MONTH(order_date) = MONTH(CURRENT_DATE())
                                        AND YEAR(order_date) = YEAR(CURRENT_DATE())
                                        AND status = 'Paid';";
                                    $result_earn_month = $conn->query($sql_earn_month);
                                    $row_earn_month = $result_earn_month->fetch_assoc();

                                    $total_earn_month = $row_earn_month['total_revenue'] * 10000;
                                    echo number_format($total_earn_month, 0, ',', '.') . " VND";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (ANNUAL) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Earnings (Year)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    $sql_earn_year = "SELECT SUM(total_amount) AS total_revenue
                                        FROM orders
                                        WHERE YEAR(order_date) = YEAR(CURRENT_DATE())
                                        AND status = 'Paid';";
                                    $result_earn_year = $conn->query($sql_earn_year);
                                    $row_earn_year = $result_earn_year->fetch_assoc();
                                    $total_earn_year = $row_earn_year['total_revenue'] * 10000;



                                    echo number_format($total_earn_year, 0, ',', '.') . " VND";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Requests</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    $sql_review_request = "SELECT id FROM `purchased_products` WHERE status = 'review'";
                                    $result_review_request = $conn->query($sql_review_request);
                                    $review_request = $result_review_request->num_rows;

                                    $sql_order_request = "SELECT id FROM `orders` WHERE status = 'Waiting for confirmation'";
                                    $result_order_request = $conn->query($sql_order_request);

                                    $order_request = $result_order_request->num_rows;

                                    echo $review_request + $order_request;
                                    ?>

                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Products</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="myPieChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> Upcoming game
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Gear
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-info"></i> Game
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Content Column -->
            <div class="col-lg-12 mb-4">

                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product Feedbacks</h6>
                    </div>
                    <?php
                    $sql_get_rating = "SELECT
                        (SUM(IF(rating = 1, 1, 0)) / total_ratings) * 100 AS '1 stars',
                        (SUM(IF(rating = 2, 1, 0)) / total_ratings) * 100 AS '2 stars',
                        (SUM(IF(rating = 3, 1, 0)) / total_ratings) * 100 AS '3 stars', 
                        (SUM(IF(rating = 4, 1, 0)) / total_ratings) * 100 AS '4 stars',
                        (SUM(IF(rating = 5, 1, 0)) / total_ratings) * 100 AS '5 stars'
                      FROM product_comments
                      CROSS JOIN (SELECT COUNT(*) AS total_ratings FROM product_comments) t;";
                    $result_rating = $conn->query($sql_get_rating);
                    $row_earn_rating = $result_rating->fetch_assoc();
                    $star_1 = round($row_earn_rating['1 stars']);
                    $star_2 = round($row_earn_rating['2 stars']);
                    $star_3 = round($row_earn_rating['3 stars']);
                    $star_4 = round($row_earn_rating['4 stars']);
                    $star_5 = round($row_earn_rating['5 stars']);
                    ?>
                    <div class="card-body">
                        <h4 class="small font-weight-bold">1 Star: Very Poor <span class="float-right">
                                <?= $star_1 ?>%
                            </span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $star_1 ?>%"
                                aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">2 Stars: Poor <span class="float-right">
                                <?= $star_2 ?>%
                            </span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $star_2 ?>%"
                                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">3 Stars: Average <span class="float-right">
                                <?= $star_3 ?>%
                            </span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: <?= $star_3 ?>%" aria-valuenow="60"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">4 Stars: Good <span class="float-right">
                                <?= $star_4 ?>%
                            </span></h4>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $star_4 ?>%"
                                aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <h4 class="small font-weight-bold">5 Stars: Excellent <span class="float-right">
                                <?= $star_5 ?>%
                            </span>
                        </h4>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $star_5 ?>%"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- Page level plugins -->
    <script src="../../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        let revenueData = [];
        <?php
        $sql_tb_chart = "SELECT 
            SUM(CASE MONTH(order_date) WHEN 1 THEN total_amount ELSE 0 END) AS 'Jan',
            SUM(CASE MONTH(order_date) WHEN 2 THEN total_amount ELSE 0 END) AS 'Feb',
            SUM(CASE MONTH(order_date) WHEN 3 THEN total_amount ELSE 0 END) AS 'Mar',
            SUM(CASE MONTH(order_date) WHEN 4 THEN total_amount ELSE 0 END) AS 'Apr',
            SUM(CASE MONTH(order_date) WHEN 5 THEN total_amount ELSE 0 END) AS 'May',
            SUM(CASE MONTH(order_date) WHEN 6 THEN total_amount ELSE 0 END) AS 'Jun', 
            SUM(CASE MONTH(order_date) WHEN 7 THEN total_amount ELSE 0 END) AS 'Jul',
            SUM(CASE MONTH(order_date) WHEN 8 THEN total_amount ELSE 0 END) AS 'Aug',
            SUM(CASE MONTH(order_date) WHEN 9 THEN total_amount ELSE 0 END) AS 'Sep',
            SUM(CASE MONTH(order_date) WHEN 10 THEN total_amount ELSE 0 END) AS 'Oct',
            SUM(CASE MONTH(order_date) WHEN 11 THEN total_amount ELSE 0 END) AS 'Nov',
            SUM(CASE MONTH(order_date) WHEN 12 THEN total_amount ELSE 0 END) AS 'Dec'
            FROM orders
            WHERE status = 'Paid'";
        $result_tb_chart = $conn->query($sql_tb_chart);
        $row_tb_chart = $result_tb_chart->fetch_assoc();
        foreach ($row_tb_chart as $value) {
            echo "revenueData.push(" . $value . ");";
        }
        ?>
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // Area Chart Example
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Earnings",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: revenueData,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                return number_format(value) +'G';
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel) + ' G-Coin';
                        }
                    }
                }
            }
        });


    </script>

    <script>
        <?php
        $sql_sl_pie_chart = "SELECT 
    SUM(IF(classify = 'game', 1, 0)) AS 'Games',
    SUM(IF(classify = 'gear', 1, 0)) AS 'Gears',
    SUM(IF(classify = 'game' AND release_date > CURDATE(), 1, 0)) AS 'Upcoming Games'
  FROM products;";
        $result_pie_chart = $conn->query($sql_sl_pie_chart);
        $row_pie_chart = $result_pie_chart->fetch_assoc();
        $games = $row_pie_chart['Games'];
        $gears = $row_pie_chart['Gears'];
        $upcoming_games = $row_pie_chart['Upcoming Games'];
        ?>
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Upcoming game", "Gear", "Game"],
                datasets: [{
                    data: [<?= $upcoming_games ?>, <?= $gears ?>, <?= $games ?>],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });

    </script>


<?php }
include("./template.php");
?>