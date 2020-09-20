function ActivitiesDistr(){
    $(document).ready(function(){
        $.ajax({ 
            type: 'GET',
            url: 'scripts/activities_dstr.php',
            dataType : 'json',
            success: function(data){

                var activities_dstr ={
                    type : [],
                    percentage : []
                };

                var total = 0;
                for(var i = 0; i < data.length; i++){
                    total += parseInt(data[i]["cnt"]);
                }

                var percentage = 0;
                for(var i = 0; i < data.length; i++){
                    percentage = (parseInt(data[i]["cnt"])/total)*100;
                    activities_dstr.percentage.push(parseFloat(percentage).toFixed(2));
                    activities_dstr.type.push(data[i]["activity_type"]);
                }
            
                var length = data.length;
                TableA(activities_dstr,length);
                ActivitiesDistGraph(activities_dstr);
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
    });
};

function ActivitiesDistGraph(activities_dstr){
    var activities = activities_dstr.type;
    var percentage = activities_dstr.percentage;

    var colors = ['#007bff','#77a36c','#cf7806','#811d8a', '#b9d4f0', '#9491fa'];

    var ctx = document.getElementById('ChartA').getContext('2d');
    var barChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: activities,
            datasets: [{
                data: percentage,
                backgroundColor: colors,
                borderWidth: 1,
                pointHoverBorderWidth: 10,
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


function TableA(activities_dstr,length){
    var table = document.getElementById('tableA');
    table.innerHTML ="";

    for (var i = 0; i < length; i++){
        var acticity = activities_dstr.type[i];
        var score =  activities_dstr.percentage[i] + "%";

        var row = table.insertRow(i);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        cell1.innerHTML = acticity.toString();
        cell2.innerHTML = score.toString();
    }

}
