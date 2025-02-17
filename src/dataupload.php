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
        <link rel="stylesheet" href="libs/leaflet-geoman/leaflet-geoman.css" />
        <script src="libs/leaflet-geoman/leaflet-geoman.min.js"></script>

        <script src="libs/heatmapjs/build/heatmap.js"></script>
        <script src="libs/heatmapjs/plugins/leaflet-heatmap/leaflet-heatmap.js"></script>

        <script src="libs/point-in-polygon/wise-leaflet-pip.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="">
                <img src="logo.png" width="25" alt="">
            </a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./userpanel.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./analyzedata.php">Analyze User Data</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./dataupload.php">Data upload</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="scripts/logout.php" method="post">
                    <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Logout</button>
                </form>
            </div>            
        </nav>

        <div id="mapdiv"></div>
        
        <div id="uploadBar" class="p-1 mb-2 bg-dark text-white">
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="form-row align-items-center">
                    <div class="col-auto mr-sm-3">
                        <input type="file" name="fileToUpload" id="fileToUpload" class="custom-file-input" style="cursor: pointer;" onchange="loadData(event)">
                        <label class="custom-file-label" for="fileToUpload">Choose file</label>
                    </div>
                    <div class="col_auto">
                        <input type="button" id="upload_btn" value="Upload JSON" class="btn btn-outline-primary my-2 my-sm-0" name="submit">
                    </div>
                </div>
            </form>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="libs/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>
        <script src="scripts/main.js"></script>
        <script src="scripts/initmap.js"></script>

        <script>
            let datamap = createMap("mapdiv", true);

            $('#fileToUpload').change(function() {
                datamap = loadData(event, datamap);
            });
            
            $("#upload_btn").on('click', function(e) {
                prunSensitiveLocations(datamap);
            });
        </script>
    </body>
</html>
