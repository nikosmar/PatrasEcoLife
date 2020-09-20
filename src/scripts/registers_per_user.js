function RegistersPerUser(){
    $(document).ready(function(){
        $.ajax({ 
            type: 'GET',
            url: 'scripts/get_registers_per_user.php',
            dataType : 'json',
            success: function(data){
                var registersPerUser = {
                    number_of_users:[],
                    registers:[]
                };

                var min_registers = parseInt(data[0]["cnt"]);
                var max_registers = parseInt(data[data.length-1]["cnt"]);
                var r = max_registers - min_registers;
                // empirical metric
                var sturges = 1+3.322*Math.log10(data.length);
                // intervals
                var k = parseInt(sturges)+1;
                // intervals width
                var delta = parseInt(r/k);

                var lower_limit = min_registers;
                var upper_limit = lower_limit + delta;

                var lower_limits = [];
                var upper_limits = [];

                for (var i = 0; i < k; i++) {
                    lower_limits.push(lower_limit);
                    upper_limits.push(upper_limit);

                    var field = lower_limit + "-" + upper_limit;
                    registersPerUser.registers.push(field);
                    registersPerUser.number_of_users.push(0);

                    lower_limit = upper_limit +1 ;
                    upper_limit = lower_limit + delta;
                }

                for (var i = 0; i < data.length; i++) {
                    for (var j = 0; j < k; j++) {
                        if (parseInt(data[i]["cnt"]) >= lower_limits[j] && parseInt(data[i]["cnt"]) <= upper_limits[j]) {
                            registersPerUser.number_of_users[j]++;
                            break;
                        }
                    }
                }

                GraphB(registersPerUser);
                TableB(registersPerUser);
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
    });
};

function TableB(registersPerUser){
    var table = document.getElementById('tableB');
    table.innerHTML ="";
    
    for (var i = 0; i < registersPerUser.registers.length; i++){
        var registers = registersPerUser.registers[i];
        var users =  registersPerUser.number_of_users[i];

        var row = table.insertRow(i);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        cell1.innerHTML = registers.toString();
        cell2.innerHTML = users.toString();
    }

}

function GraphB(registersPerUser){
    var users = registersPerUser.number_of_users;
    var registers = registersPerUser.registers;

    var ctx = document.getElementById('ChartB').getContext('2d');
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: registers,
            datasets: [{
                data: users,
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
