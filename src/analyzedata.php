<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
        header('Location: .');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Patras' Eco Life</title>

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <link href="libs/bootstrap-4.5.2-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="styles/style.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" href="libs/leaflet/leaflet.css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.css" rel="stylesheet"/>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./userpanel.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./analyzedata.php">Analyze User Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./dataupload.php">Data upload</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="scripts/logout.php" method="post">
                    <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Logout</button>
                </form>
            </div>
        </nav>

        <div id="dateFilter">
            <input type="text" id="datepicker">
            <input type="number" id="hour" name="hour" min="0" max="23" placeholder="18">
            <input type="number" id="minutes" name="minutes" min="0" max="59" placeholder="30">
            <input type="button" value="Filter" class="btn btn-outline-primary my-2 my-sm-0" id="filter">
        </div>

        <div id="dataContainer" class="container-fluid pt-3">
            <div id="dataContainerLeft" class="container">
                <div id="percentageTableCon" class="table-responsive-sm">
                    <table id="percentageTable" class="table table-dark table-striped">
                        <thead>
                            <tr>
                            <th scope="col">Activity Type</th>
                            <th scope="col">Percentage (%)</th>
                            </tr>
                        </thead>
                        <tbody id="percentage_table_rows">
                        </tbody>
                    </table>
                </div>
                <div class="container">
                    <div class="row my-3">
                        <div class="col">
                            <h4>Percentage of total activities per activity type</h4>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <canvas id="chLine" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="dataContainerRight" class="container">
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="libs/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>
        <script src="libs/leaflet/leaflet.js"></script>
        <script src="libs/heatmapjs/build/heatmap.js"></script>
        <script src="libs/heatmapjs/plugins/leaflet-heatmap/leaflet-heatmap.js"></script>
        <script src="scripts/main.js"></script>
        <script src="scripts/initmap.js"></script>

        <script>
            $("#datepicker").datepicker({
                todayBtn: true,
                todayHighlight: true,
                weekStart: 1,
                format: "yyyy-mm-dd",
                startDate: "2005-01-01",
                endDate: "0"
            });
        </script>

        <script>
            function initActivityChart(activityTypes, percentages) {
                // chart colors
                var colors = ['#007bff','#77a36c','#333333','#c3e6cb','#dc3545','#6c757d'];

                /* large line chart */
                var chLine = document.getElementById("chLine");
                var chartData = {
                    labels: activityTypes,
                    datasets: [{
                        data: percentages,
                        backgroundColor: colors[1]
                    }]
                };

                if (chLine) {
                    new Chart(chLine, {
                        type: 'bar',
                        data: chartData,
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        max: 100
                                    }
                                }]
                            },
                            legend: {
                                display: false
                            }
                        }
                    });
                }
            }
        </script>

        <script>
            let datamap = createMap("dataContainerRight", false);

            $('#filter').click(function() {
                fillPercentageTableChart();
                datamap = generateHeatmap(datamap);
            });

            function activityTimeSpan() {
                var date_from = document.getElementById("datepicker").value;
                var hour_from = document.getElementById("hour").value;
                var mins_from = document.getElementById("minutes").value;

                var date_to;
                var hour_to;
                var mins_to;

                if (hour_from == "" || mins_from == "") {
                    hour_from = "00";
                    mins_from = "00:00";
                    hour_to = "23";
                    mins_to = "59:59";
                }
                else {
                    mins_to = mins_from + ":59";
                    mins_from += ":00";
                    hour_to = hour_from;
                }

                if (date_from == "") {
                    date_from = "2005-01-01";
                    
                    var today = new Date();
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0');
                    var yyyy = today.getFullYear();

                    today = yyyy + '-' + mm + '-' + dd;
                    date_to = today;
                }
                else {
                    date_to = date_from;
                }

                var ts_from = date_from + " " + hour_from + ":" + mins_from;
                var ts_to = date_to + " " + hour_to + ":" + mins_to;

                return [ts_from, ts_to];
            }

            function fillPercentageTableChart() {
                var [timeStart, timeEnd] = activityTimeSpan();
                var activities = [];
                var percentages = [];
                var results = []

                $.ajax({
                    type: 'POST',
                    url: 'scripts/get_activities_in_time_range.php',
                    data: {timeStart, timeEnd},
                    success: function(data) {
                        results = JSON.parse(data);

                        var table = document.getElementById("percentage_table_rows");
                        // empty table before filling with the results from the new query
                        table.innerHTML = "";

                        for (var i in results) {
                            var activity = results[i].type;
                            var percentage = results[i].percentage;

                            // fill activities table
                            var row = table.insertRow(i);
                            var cell1 = row.insertCell(0);
                            var cell2 = row.insertCell(1);
                            cell1.innerHTML = activity.toString();
                            cell2.innerHTML = percentage.toString();

                            // arrays needed for chart initialization
                            activities.push(activity);
                            percentages.push(percentage);
                        }

                        initActivityChart(activities, percentages);
                    },
                    error: function() {
                        alert('An error occured.');
                    }
                });
            }

            function generateHeatmap(map) {
                var [timeStart, timeEnd] = activityTimeSpan();
                var userLocations = [];

                $.ajax({
                    type: 'POST',
                    url: 'scripts/get_user_locations.php',
                    data: {timeStart, timeEnd},
                    success: function(data) {
                        userLocations = JSON.parse(data);

                        map = loadDataFromDB(userLocations, map);
                        return map;
                    },
                    error: function() {
                        alert('An error occured.');
                    }
                });
            }
        </script>


    </body>
</html>
