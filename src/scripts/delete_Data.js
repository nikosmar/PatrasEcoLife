$(function() {
	$('#deleteData').click(function(e) {
		e.preventDefault();
   		$.ajax({ 
    	type: 'GET',
        url: 'scripts/delete_data.php',
        success: function(data){
        	if(data=="deleted"){
        		alert('Data successfully deleted');
        	}
        },
        error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
        }
		});
	});
});
