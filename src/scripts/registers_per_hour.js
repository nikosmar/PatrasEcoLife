function RegistersPerHour(){
    $(document).ready(function(){
        $.ajax({ 
            type: 'GET',
            url: 'scripts/get_registers_per_hour.php',
            dataType : 'json',
            success: function(data){
                var registersPerHour = {
                    hour : [],
                    total : []
                };
                
                for(var i = 0; i < data.length; i++){
                    var hour = data[i]["hour(ts)"] + ":00" +"-"+ data[i]["hour(ts)"] + ":59";
                    registersPerHour.hour.push(hour);
                    registersPerHour.total.push(data[i]["cnt"]);
                }
                GraphE(registersPerHour);
                TableE(data);
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
    });
};

function TableE(data){
    var table = document.getElementById('tableE');
    table.innerHTML ="";
    
    for (var i = 0; i < data.length; i++){
        var hour = data[i]["hour(ts)"] + ":00" +"-"+ data[i]["hour(ts)"] + ":59";
        var total =  data[i]["cnt"];

        var row = table.insertRow(i);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        cell1.innerHTML = hour.toString();
        cell2.innerHTML = total.toString();
    }

}

function GraphE(registersPerHour){
    var hours = registersPerHour.hour;
    var registers = registersPerHour.total;

    var ctx = document.getElementById('ChartE').getContext('2d');
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: hours,
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
