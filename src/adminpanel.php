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
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="./adminpanel.php">Dashboard <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
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

        <div class="container-fluid pt-3"  style="background-color: #292b2c">
          <div class="row align-items-start">
            <div class="col-sm-3">
                <h2 id="GraphLabel"><u>Users Activities Percentage</u></h2>
                <canvas id="ChartA"></canvas>
                <div class="col-lg-3">
                    <table  class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Acticity</th>
                                <th scope="col">Percentage</th>
                            </tr>
                        </thead>
                        <tbody  id="tableA">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col"> 
             <h2 id="GraphLabel"><u>Number of users per Registers</u></h2>
              <canvas id="ChartB"></canvas>
              <div class="col">
                  <table  class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Rank</th>
                                <th scope="col">Name</th>
                            </tr>
                        </thead>
                        <tbody  id="tableB">
                                    
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col">
                <h2 id="GraphLabel"><u>Registers per month</u></h2>
                <canvas id="ChartC"></canvas>
                <div class="col">
                  TABLE C
                  <table  class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Month</th>
                                <th scope="col">Registers</th>
                            </tr>
                        </thead>
                        <tbody  id="tableC">
                                    
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col">
                <h2 id="GraphLabel"><u>Registers per day of the week</u></h2>
                <canvas id="ChartD"></canvas>
                <div class="col">
                    TABLE D
                    <table  class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Day</th>
                                <th scope="col">Registers</th>
                            </tr>
                        </thead>
                        <tbody  id="tableD">
                                        
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-3">
                <h2 id="GraphLabel"><u>Registers per hour</u></h2>
                <canvas id="ChartE"></canvas>
                <div class="col-sm-3">
                    TABLE E
                    <table  class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Hours</th>
                                <th scope="col">Registers</th>
                            </tr>
                        </thead>
                        <tbody  id="tableE">
                                        
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-3">
              <h2 id="GraphLabel"><u>Registers per year</u></h2>
                <canvas id="ChartF"></canvas>
                <div class="col-sm-3">
                    TABLE F
                    <table  class="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Year</th>
                                <th scope="col">Registers</th>
                            </tr>
                        </thead>
                        <tbody  id="tableF">
                                        
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="libs/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>
        <script src="scripts/main.js"></script>
        <script src="scripts/delete_Data.js"></script>
        <script src="scripts/activities_distribution.js"></script>
        <script src="scripts/registers_per_month.js"></script>
        <script src="scripts/registers_per_day.js"></script>
        <script src="scripts/registers_per_hour.js"></script>
        <script src="scripts/registers_per_year.js"></script>
        <script src="scripts/registers_per_user.js"></script>
        <script> ActivitiesDistr(); RegistersPerMonth(); RegistersPerDay(); RegistersPerHour(); RegistersPerYear(); RegistersPerUser();</script>

    </body>
</html>
