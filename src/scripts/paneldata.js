function UpdateTheGraph(MonthlyEcoScores){
    var Months = MonthlyEcoScores.Month;
    var Score = MonthlyEcoScores.EcoScore;
    var ctx = document.getElementById('myChart').getContext('2d');
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: Months,
            datasets: [{
                label: 'Monthly Score',
                data: Score,
                backgroundColor: 'rgba(87, 172, 83, 0.2)',
                borderColor: [
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(0, 0, 0, 0.1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 3,
                pointHoverBorderWidth: 10,
                // pointHoverBackgroundColor: 'rgba(255, 255, 86, 0.2)',
                // pointHoverBorderWidth: 'rgba(255, 255, 255, 0.2)'
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        max: 100
                    }
                }]
            }
        }
    });
}

function showUserScore(){
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
                                data2.Month.push(parseInt(checkMonth));
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
                            data2.Month.push(parseInt(checkMonth));
                            data2.EcoScore.push(parseFloat(EcoScore).toFixed(2));
                            // set the current month's EcoScore (if query is asc) 
                            if(data[i]["YEAR(ts)"] == currentYear && data[i]["MONTH(ts)"] == currentMonth){
                                    currentEcoScore = eco_counter/actity_counter*100;
                                    currentEcoScore = parseFloat(currentEcoScore).toFixed(2);
                            }
                            break;
                        }
                    }
                    if(currentEcoScore != 0){   
                        document.getElementById('userScore').innerHTML  = currentEcoScore +'%';
                    }else{
                        document.getElementById('userScore').innerHTML  = 'You do not have eco-score for the current month.';
                    }
                    FormTheData(data2);
                } else{
                    console.log('You dont have any data');
                };
    	    },
    	    error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
    	});
    });
};

function FormTheData(data){
    var months = ['January', 'February ', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var today = new Date();
    var currentMonth = String(today.getMonth() + 1); //January is 0!
    var MonthlyEcoScores = {
                    Month : [],
                    EcoScore : []
                };

    // initializate the MonthlyEcoScores
    for(var i = 0; i < 12; i++ ){
        MonthlyEcoScores.Month.push(0);
        MonthlyEcoScores.EcoScore.push(0);
    }

    if(data.Month.length != 12){
        for (var i = 0; i < data.Month.length; i++){
            if(parseInt(currentMonth) < data.Month[i]){
                var index = data.Month[i]-currentMonth-1;
                MonthlyEcoScores.Month[index] = data.Month[i];
                MonthlyEcoScores.EcoScore[index] = data.EcoScore[i];

            }else{
                var index = data.Month[i]+11-currentMonth;
                MonthlyEcoScores.Month[index] = data.Month[i];
                MonthlyEcoScores.EcoScore[index] = data.EcoScore[i];
            }
        }
        for(var i = 0; i < 12; i++ ){
            if(MonthlyEcoScores.Month[i] == 0){
                var month = ((parseInt(currentMonth)+i+12)%12)+1;
                MonthlyEcoScores.Month[i] = month;
            }
            MonthlyEcoScores.Month[i] = months[MonthlyEcoScores.Month[i]-1];
        }
        UpdateTheGraph(MonthlyEcoScores);
    }else{
        for(var i = 0; i < 12; i++){
            data.Month[i] = months[data.Month[i]-1];
        }
        UpdateTheGraph(data);
    }
}

