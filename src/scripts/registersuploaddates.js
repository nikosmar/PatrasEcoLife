// Period fo Registers conn with db
function PeriodOfRegisters(){
    $(document).ready(function(){
        $.ajax({ 
            type: 'GET',
            url: 'scripts/registersperiod.php',
            dataType : 'json',
            success: function(data){
               var RegistersPeriod = [];

               if(data.length != 0 ){
                RegistersPeriod = data[0]["date(ts)"] + " TILL " + data[1]["date(ts)"];
                document.getElementById('registers').innerHTML  = RegistersPeriod;
               }else{
                RegistersPeriod = 'You do not have any register';
                document.getElementById('registers').innerHTML  = RegistersPeriod;
               }
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
    });
};
// Last Upload
function UploadDate(){
$(document).ready(function(){
        $.ajax({ 
            type: 'GET',
            url: 'scripts/uploaddate.php',
            dataType : 'json',
            success: function(data){
 
                if (data.length != 0 ) {
                    var LastUpload = data[0]["date(last_upload)"];
                    document.getElementById('lastUpload').innerHTML  = LastUpload;
                }else{
                    var LastUpload = 'You do not have any uploads';
                    document.getElementById('lastUpload').innerHTML  = LastUpload;
                }
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
    });
};