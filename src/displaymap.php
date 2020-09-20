<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false || $_SESSION['user_type'] != 1) {
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

        <link href="libs/leaflet/leaflet.css" rel="stylesheet"/>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./adminpanel.php">Dashboard <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./displaymap.php">Display Map</a>
                    </li>
                    <button type="submit" data-target="#myModal"  class="btn btn-outline-danger my-2 my-sm-0" data-toggle="modal">Delete Data</button>
                <!-- Modal -->
                    <div id="myModal" class="modal" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Data</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Do you want to delete all the data?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                    <button id="deleteData" type="submit" class="btn btn-danger" data-dismiss="modal">Yes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="scripts/logout.php" method="post">
                    <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Logout</button>
                </form>
            </div>
        </nav>

        <div id="adminDataContainer" class="container-fluid ">  
            <div class="row h-100">
                <div id="sidebarCol" class="col-3">
                    <div id="adminSidebar" class="container-fluid pt-3">
                        <div class="row">
                            <div class="col">
                                <br>
                                Select year range:
                            </div>
                        </div>

                        <div class="row">
                            <div id="yearFrom" class="col">
                                <select class="custom-select text-light bg-dark" id="selectYearFrom"></select>
                            </div>
                            <div id="yearTo" class="col">
                                <select class="custom-select text-light bg-dark" id="selectYearTo"></select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <br>
                                Select month range:
                            </div>
                        </div>

                        <div class="row">
                            <div id="monthFrom" class="col">
                                <select class="custom-select text-light bg-dark" id="selectMonthFrom">
                                    <option selected value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div id="monthTo" class="col">
                                <select class="custom-select text-light bg-dark" id="selectMonthTo">
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option selected value="12">December</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <br>
                                Select weekday(s):
                            </div>
                        </div>

                        <div class="row">
                            <div id="weekdayFrom" class="col">
                                <select class="custom-select text-light bg-dark" id="selectWeekdayFrom">
                                    <option selected value="0">Monday</option>
                                    <option value="1">Tuesday</option>
                                    <option value="2">Wednesday</option>
                                    <option value="3">Thursday</option>
                                    <option value="4">Friday</option>
                                    <option value="5">Saturday</option>
                                    <option value="6">Sunday</option>
                                </select>
                            </div>
                            <div id="weekdayTo" class="col">
                                <select class="custom-select text-light bg-dark" id="selectWeekdayTo">
                                    <option value="0">Monday</option>
                                    <option value="1">Tuesday</option>
                                    <option value="2">Wednesday</option>
                                    <option value="3">Thursday</option>
                                    <option value="4">Friday</option>
                                    <option value="5">Saturday</option>
                                    <option selected value="6">Sunday</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <br>
                                Select time range:
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <input class="form-control text-light bg-dark" type="number" value="00" min="00" max="23" id="hourFrom">
                                    </div>
                                    <div class="col">
                                        <input class="form-control text-light bg-dark" type="number" value="00" min="00" max="59" id="minsFrom">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <input class="form-control text-light bg-dark" type="number" value="23" min="00" max="23" id="hourTo">
                                    </div>
                                    <div class="col">
                                        <input class="form-control text-light bg-dark" type="number" value="59" min="00" max="59" id="minsTo">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div id="actTypeCol" class="col">
                                <br>
                                <div class="form-check" id="activityForm">
                                    <input class="form-check-input" type="checkbox" name="activitiesSel" id="select-all"/>
                                    <label class="text-light" for="select-all"> Select all available types</label>
                                    <br>
                                    
                                    <input type="checkbox" id="ON_FOOT" value="ON_FOOT" name="activities" class="form-check-input">
                                    <label for="ON_FOOT" class="text-light">ON_FOOT</label>
                                    <br>

                                    <input type="checkbox" id="WALKING" value="WALKING" name="activities" class="form-check-input">
                                    <label for="WALKING" class="text-light">WALKING</label>
                                    <br>

                                    <input type="checkbox" id="STILL" value="STILL" name="activities" class="form-check-input">
                                    <label for="STILL" class="text-light">STILL</label>
                                    <br>

                                    <input type="checkbox" id="IN_VEHICLE" value="IN_VEHICLE" name="activities" class="form-check-input">
                                    <label for="IN_VEHICLE" class="text-light">IN_VEHICLE</label>
                                    <br>

                                    <input type="checkbox" id="RUNNING" value="RUNNING" name="activities" class="form-check-input">
                                    <label for="RUNNING" class="text-light">RUNNING</label>
                                    <br>

                                    <input type="checkbox" id="ON_BICYCLE" value="ON_BICYCLE" name="activities" class="form-check-input">
                                    <label for="ON_BICYCLE" class="text-light">ON_BICYCLE</label>
                                    <br>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <button type="button" id="apply" class="btn btn-outline-primary my-2 my-sm-0 float-right">Apply Filters</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <br>
                                <select class="custom-select text-light bg-dark float-left" id="selectFileFormat">
                                    <option selected value="0">CSV</option>
                                    <option value="1">XML</option>
                                    <option value="2">JSON</option>
                                </select>
                            </div>
                            <div class="col">
                                <br>
                                <button type="button" id="download" class="btn btn-outline-primary my-2 my-sm-0 float-right">Download</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="mapCol" class="col-9">
                    <div id="mapp" class="h-100"></div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="libs/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>
        
        <script src="libs/leaflet/leaflet.js"></script>
        <script src="libs/heatmapjs/build/heatmap.js"></script>
        <script src="libs/heatmapjs/plugins/leaflet-heatmap/leaflet-heatmap.js"></script>
        <script src="scripts/delete_Data.js"></script>

        <script src="scripts/initmap.js"></script>

        <script>
            // fill year selectors
            var today = new Date();
            var yyyy = today.getFullYear();
            
            var selectYearFrom = document.getElementById("selectYearFrom");
            var selectYearTo = document.getElementById("selectYearTo");

            for (var i = 2005; i <= yyyy; i++) {
                var optionFrom = document.createElement("option");
                optionFrom.text = i.toString();
                optionFrom.value = i.toString();
                
                var optionTo = document.createElement("option");
                optionTo.text = i.toString();
                optionTo.value = i.toString();

                selectYearFrom.add(optionFrom);
                selectYearTo.add(optionTo);
            }

            selectYearFrom.selectedIndex = 0;
            selectYearTo.selectedIndex = yyyy - 2005;
        </script>

        <script>
            $('#select-all').click(function(event) {   
                if (this.checked) {
                    // Iterate each checkbox
                    $(':checkbox').each(function() {
                        this.checked = true;                        
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;                       
                    });
                }
            });
        </script>

        <script>
            let datamap = createMap("mapp", false);

            $('#apply').click(function() {
                var year_from = document.getElementById("selectYearFrom").value;
                var year_to = document.getElementById("selectYearTo").value;

                var month_from = document.getElementById("selectMonthFrom").value;
                var month_to = document.getElementById("selectMonthTo").value;
                
                var day_from = document.getElementById("selectWeekdayFrom").value;
                var day_to = document.getElementById("selectWeekdayTo").value;

                var hour_from = document.getElementById("hourFrom").value;
                var mins_from = document.getElementById("minsFrom").value;
                var hour_to = document.getElementById("hourTo").value;
                var mins_to = document.getElementById("minsTo").value;
                var ts_from = hour_from + ":" + mins_from + ":00";
                var ts_to = hour_to + ":" + mins_to + ":59";

                var selectedActivities = [];

                $("input:checkbox[name=activities]:checked").each(function(){
                    selectedActivities.push($(this).val());
                });

                $.ajax({
                    type: 'POST',
                    url: 'scripts/get_all_users_locations.php',
                    data: {year_from, year_to, month_from, month_to, day_from, day_to, ts_from, ts_to, selectedActivities},
                    success: function(data) {
                        userLocations = JSON.parse(data);

                        var lastLayer;
                        var count = 0;

                        datamap.eachLayer(function(layer){
                            lastLayer = layer;
                            count++;
                        })

                        if (count > 1) {
                            datamap.removeLayer(lastLayer);
                        }

                        if (userLocations.length) {
                            datamap = loadDataFromDB(userLocations, datamap);
                        }
                    },
                    error: function() {
                        alert('An error occured.');
                    }
                });
            });
        </script>

        <script>
            function downloadFile(fileName) {
                var link = document.createElement('a');
                link.href = "userdata/" + fileName;
                link.download = fileName;
                link.click();
            }

            $('#download').click(function() {
                var filetype = document.getElementById("selectFileFormat").value;

                $.ajax({
                    type: 'POST',
                    url: 'scripts/prepare_file.php',
                    data: {filetype},
                    success: function(data) {
                        downloadFile(data);
                    },
                    error: function() {
                        alert('An error occured.');
                    }
                });
            });
        </script>
    </body>
</html>
