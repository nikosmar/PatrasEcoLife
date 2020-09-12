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
            data: [{"activitie_type":"", "ts":""}],
    	    success: function(data){
                alert('tipota');
    	    	console.log(data);
    	    },
    	    error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                alert("to mpoulo");
            }
    	});
    });
}
