function RegistersPerDay(){
    $(document).ready(function(){
        $.ajax({ 
            type: 'GET',
            url: 'scripts/get_registers_per_day.php',
            dataType : 'json',
            success: function(data){
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Sunday'];
                var registersPerDay = {
                    day : [],
                    total : []
                };
                
                var flag = 0 ;
                for(var i = 0; i < days.length; i++){
                    if(i+1 == parseInt(data[flag]["dayofweek(ts)"])){
                        data[flag]["dayofweek(ts)"] = days[i];
                        flag +=1;
                        if(flag == data.length){
                            break;
                        }
                    }
                }
                for(var i = 0; i < data.length; i++){
                    registersPerDay.day.push(data[i]["dayofweek(ts)"]);
                    registersPerDay.total.push(data[i]["cnt"]);
                }
                GraphD(registersPerDay);
                TableD(data);
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
    });
};

function TableD(data){
    var table = document.getElementById('tableD');
    table.innerHTML ="";
    for (var i = 0; i < data.length; i++){
        var day = data[i]["dayofweek(ts)"];
        var total =  data[i]["cnt"];

        var row = table.insertRow(i);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        cell1.innerHTML = day.toString();
        cell2.innerHTML = total.toString();
    }

}

function GraphD(registersPerDay){
    var days = registersPerDay.day;
    var registers = registersPerDay.total;

    var ctx = document.getElementById('ChartD').getContext('2d');
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: days,
            datasets: [{
                data: registers,
                backgroundColor: 'rgba(87, 172, 83, 0.2)',
                borderWidth: 1,
                pointHoverBorderWidth: 10,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            },
            legend: {
                display: false
            }
        }
    });
}
