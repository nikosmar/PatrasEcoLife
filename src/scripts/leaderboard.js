function UpdateLeader(){
    // console.log('mphka');
	$(document).ready(function(){
        $.ajax({ 
            type: 'GET',
            url: 'scripts/updateleader.php',
            dataType : 'json',
            success: function(data){
                console.log(data);
                var table = document.getElementById('leaderBoard');
                table.innerHTML ="";
                for (var i = 0; i < data.length; i++){
                    var rank = data[i]["rank"];
                    var name =  data[i]["name"];
                    var score =  (parseFloat(data[i]["score"])*100).toFixed(2) +"%";
                    // console.log(data);
                    if(data[i]["user"] === false){
                        var row = table.insertRow(i);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);

                        cell1.innerHTML = rank.toString();
                        cell2.innerHTML = name.toString();
                        cell3.innerHTML = score.toString();
                    }else{
                        var row = table.insertRow(i);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);

                        cell1.innerHTML = rank.toString();
                        cell2.innerHTML = name.toString();
                        cell3.innerHTML = score.toString();
                        row.style.color = "#00AA00";
                    }
                }
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
    });
};
