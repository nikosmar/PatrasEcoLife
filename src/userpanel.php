<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false || $_SESSION['user_type'] == 1) {
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
                        <a class="nav-link" href="./userpanel.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
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
        <div id="userData" class="container-fluid">
            <div class="shadow p-3 mb-5 rounded" style="background-color: #505a62">
                <h2 style="color :#00AA00 ">Welcome, <?php echo $_SESSION ['username']; ?> </h2>
                <p class="text-body">PatrasEcoLife is a website with environment-conscious and we are here to help you to raise environmental conscience.
                <br>Let's see your metrics and your reverence to the environment. </p>
            </div> 
            <div class="row">
                <div class="col">
                    <div class="col-xs-6 col-xs" style="background-color: #343a40">
                    <canvas id="myChart"></canvas> 
                    </div>   
                </div>
                <div class="col-lg-3 col-lg-offset-3">
                    <div class="col-xs-6 col-xs" style="background-color: #343a40">
                        <table class="table table-striped table-dark">
                            <tbody>
                                <tr>
                                    <th scope="row">Score of the Month:</th>
                                    <td id="userScore"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Your Registers:</th>
                                    <td id="registers"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Last Upload:</th>
                                    <td id="lastUpload"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <div class = "w-100">
                    <div class="col-xs-6 col-xs" style="background-color: #343a40">
                        <table  class="table table-striped table-dark">
                            <thead>
                                <tr>
                                    <th scope="col">Rank</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Eco Score</th>
                                </tr>
                            </thead>
                            <tbody  id="leaderBoard">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>

    </body>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="libs/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>
        <script src="scripts/main.js"></script>
        <script src="scripts/paneldata.js"></script>
        <script src="scripts/registersuploaddates.js"></script>
        <script src="scripts/leaderboard.js"></script>
        <script> showUserScore(); PeriodOfRegisters(); UploadDate(); UpdateLeader();</script>

</html>
