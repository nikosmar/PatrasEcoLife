function RegistersPerYear(){
    $(document).ready(function(){
        $.ajax({ 
            type: 'GET',
            url: 'scripts/get_registers_per_year.php',
            dataType : 'json',
            success: function(data){
                var registersPerYear = {
                    years : [],
                    total : []
                };
                
                for(var i = 0; i < data.length; i++){
                    registersPerYear.years.push(data[i]["year(ts)"]);
                    registersPerYear.total.push(data[i]["cnt"]);
                }
                GraphF(registersPerYear);
                TableF(data);
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
    });
};

function TableF(data){
    var table = document.getElementById('tableF');
    table.innerHTML ="";
    
    for (var i = 0; i < data.length; i++){
        var year = data[i]["year(ts)"];
        var total =  data[i]["cnt"];

        var row = table.insertRow(i);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        cell1.innerHTML = year.toString();
        cell2.innerHTML = total.toString();
    }

}

function GraphF(registersPerYear){
    var years = registersPerYear.years;
    var registers = registersPerYear.total;

    var ctx = document.getElementById('ChartF').getContext('2d');
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: years,
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