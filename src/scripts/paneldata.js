var ctx = document.getElementById('myChart').getContext('2d');
var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['January', 'February ', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            label: 'Monthly Score',
            data: [12, 19, 3, 5, 2, 3, 155, 12, 55, 40, 30, 77],
            // backgroundColor: [
            //     'rgba(255, 99, 132, 0.2)',
            //     'rgba(54, 162, 235, 0.2)',
            //     'rgba(255, 206, 86, 0.2)',
            //     'rgba(75, 192, 192, 0.2)',
            //     'rgba(153, 102, 255, 0.2)',
            //     'rgba(255, 159, 64, 0.2)'
            // ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});



function showUserScore(str) {
    /*if (str == "") {
        document.getElementById("userScore").innerHTML = "";
        return;
    }
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("userScore").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "scripts/user_panel_data.php", true);
    xhttp.send(null);*/

    $(document).ready(function() {
        $.ajax({ 
            type: 'GET',
    	    url: 'scripts/user_panel_data.php',
            dataType : 'json',
    	    success: function(data){

                var data2 = {
                    Month : [],
                    EcoScore : []
                };

                var today = new Date();
                var currentMonth = String(today.getMonth() + 1); //January is 0!
                var currentYear = today.getFullYear();
               
                var checkMonth = data[0]["MONTH(ts)"];
                var actity_counter = 0;
                var eco_counter = 0;
                var EcoScore = 0;
                var currentEcoScore = 0 ;

                if(data && data.length){
                    for (var i = 0; i < data.length; i++){
                        if(data[i]["YEAR(ts)"] == currentYear && data[i]["MONTH(ts)"] == currentMonth){
                            actity_counter +=  parseInt(data[i]["cnt"]);
                            if(data[i]["activity_type"].includes('VEHICLE') != true){
                                eco_counter += parseInt(data[i]["cnt"]);
                            }
                        }else {
                            actity_counter +=  parseInt(data[i]["cnt"]);

                            if(data[i]["activity_type"].includes('VEHICLE') != true){
                                eco_counter += parseInt(data[i]["cnt"]);
                            }
                        }
                        if(i+1 != data.length){
                            if(data[i+1]["MONTH(ts)"] != checkMonth ){
                                // set the values of the month 
                                EcoScore = eco_counter/actity_counter*100;
                                data2.Month.push(checkMonth);
                                data2.EcoScore.push(parseFloat(EcoScore).toFixed(2));
                                // set the current month's EcoScore
                                if(data[i]["YEAR(ts)"] == currentYear && data[i]["MONTH(ts)"] == currentMonth){
                                    currentEcoScore = eco_counter/actity_counter*100;
                                    currentEcoScore = parseFloat(currentEcoScore).toFixed(2);
                                   
                                }
                                // reset the counters
                                actity_counter = 0;
                                eco_counter = 0;
                                checkMonth = data[i+1]["MONTH(ts)"];     
                            }
                        }else{
                            EcoScore = eco_counter/actity_counter*100;
                            data2.Month.push(checkMonth);
                            data2.EcoScore.push(parseFloat(EcoScore).toFixed(2));
                            // set the current month's EcoScore (if query is asc) 
                            if(data[i]["YEAR(ts)"] == currentYear && data[i]["MONTH(ts)"] == currentMonth){
                                    currentEcoScore = eco_counter/actity_counter*100;
                                    currentEcoScore = parseFloat(currentEcoScore).toFixed(2);
                                    console.log(currentEcoScore);
                            }
                            break;
                        }
                    }
                        console.log(data2);
                        console.log(currentEcoScore);                   
                } else{
                    console.log('You dont have any data');
                }
                // console.log(data2);
    	    },
    	    error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
    	});
    });
}
