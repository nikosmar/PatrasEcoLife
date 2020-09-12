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
        <script src="libs/leaflet/leaflet.js"></script>

        <script src="libs/heatmapjs/build/heatmap.js"></script>
        <script src="libs/heatmapjs/plugins/leaflet-heatmap/leaflet-heatmap.js"></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" rel="stylesheet"/>
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

        <div id="yolo">
            <input type="text" id="datepicker">
            <input type="number" id="hour" name="hour" min="0" max="23" placeholder="18">
            <input type="number" id="minutes" name="minutes" min="0" max="59" placeholder="30">

            <input type="button" value="calc" class="btn btn-outline-primary my-2 my-sm-0" name="calc" onclick="showCustomer()">
        </div>

        <p id="res"> RESULT </p>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="libs/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>
        <script src="scripts/main.js"></script>
        <script src="scripts/initmap.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>

        <script>
            $("#datepicker").datepicker({
                todayBtn: true,
                todayHighlight: true,
                weekStart: 1,
                format: "yyyy-mm-dd",
                startDate: "01/01/2005",
                endDate: "0"
            });
        </script>

        <script>
            function showCustomer(str) {
                var xhttp;
                if (str == "") {
                    document.getElementById("res").innerHTML = "";
                    return;
                }

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

                console.log(ts_from);
                console.log(ts_to);

                xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("res").innerHTML = this.responseText;
                    }
                };
                xhttp.open("POST", "scripts/get_detailed_user_data.php", true);
                xhttp.send();
            }
        </script>
    </body>
</html>
