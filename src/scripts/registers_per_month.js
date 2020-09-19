function RegistersPerMonth(){
    $(document).ready(function(){
        $.ajax({ 
            type: 'GET',
            url: 'scripts/get_registers_per_month.php',
            dataType : 'json',
            success: function(data){
                
                var monthlyRegisters = {
                    month : [] ,
                    total : []
                };

                var total = 0;
                var check_month = parseInt(data[0]["month(ts)"]);
                for(var i = 0; i < data.length; i++){
                    if((parseInt(data[i]["month(ts)"]) == check_month) && (i+1 != data.length)){
                        total += parseInt(data[i]["cnt"]);
                    }else if(i+1 != data.length){
                        monthlyRegisters.month.push(check_month);
                        monthlyRegisters.total.push(total);
                        check_month = parseInt(data[i]["month(ts)"]);
                        total = 0;
                        total += parseInt(data[i]["cnt"]);
                    }else{
                        total += parseInt(data[i]["cnt"]);
                        monthlyRegisters.month.push(check_month);
                        monthlyRegisters.total.push(total);
                    }
                }

                var months = ['January', 'February ', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                var flag = 0;
                for(var i = 0; i<months.length; i++){
                    if(i+1 == parseInt(monthlyRegisters.month[flag])){
                        monthlyRegisters.month[flag] = months[i];
                        flag +=1;
                        if(flag == monthlyRegisters.month.length){
                            break;
                        }
                    } 
                }
                TableC(monthlyRegisters);
                GraphC(monthlyRegisters);
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
    });
};

function TableC(monthlyRegisters){
    var table = document.getElementById('tableC');
    table.innerHTML ="";

    for (var i = 0; i < monthlyRegisters.month.length; i++){
        var month = monthlyRegisters.month[i];
        var total =  monthlyRegisters.total[i];

        var row = table.insertRow(i);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);

        cell1.innerHTML = month.toString();
        cell2.innerHTML = total.toString();
    }

}

function GraphC(monthlyRegisters){
    var months = monthlyRegisters.month;
    var registers = monthlyRegisters.total;

    var ctx = document.getElementById('ChartC').getContext('2d');
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
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
            }
        }
    });
}
