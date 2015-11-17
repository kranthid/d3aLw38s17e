$(document).ready(function() {
   var table = $('#deals').DataTable({
        "ajax": {
        			"url":"http://localhost/php/Deal_market/public/api/users/all",
        			"dataSrc": "user"
        },
        "columns": [
            { "data": "Id" },
            { "data": "user_login" }
        ],
		  "columnDefs": [{
		    "targets": 2,
		    "render": function ( data, type, full, meta ) {
		    	var updateAndDeleteLinks = '<a class="btn btn-info update" id='+full.Id+'>Update</a>' + '<a style ="margin-left:10px;" class="btn btn-danger delete" id='+full.Id+'>Delete</a>'
		      return updateAndDeleteLinks;
		    },
		}]
	
    });
/*   $('.update').on('click',function(){
   		console.log("??????????????????????")
   })*/
    $('#modalSave').on('click',function(){
 			var id = $('#Id').val();
            var objData = {
                            "Id": $('#Id').val(),
                            "user_login": $('#user_login').val(),
                            "provide_id": $('#provide_id').val()
                        };
            var phpUpdateUrl = "http://localhost/php/Deal_market/public/api/users/userid/"+id
            $.ajax({
                url : phpUpdateUrl,
                type: "PUT",
                data : JSON.stringify(objData),
                success: function(data, textStatus, jqXHR)
                {
                    console.log("Data sent to the server >>>>>>>>>>>>>>>>",data);
                    alert("You have updated successfully");
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    console.log("Error while sending data to the server>>>>>>>>>>>>>>>>",errorThrown)
                }
            });
});
 	$('#deals').on('click', 'a', function () {
 		console.log(">>>>>>>>>>>>>>>>..")
        if( $(this).attr("class").indexOf('update') != -1){
        	console.log("We are in update>>>>>>>>");
        		$('#basicModal').modal('show');
        		var id = this.id
		        $.ajax({
		            url: 'http://localhost/php/Deal_market/public/api/user/userid/' + id,
		            method: 'GET'
		        }).success(function(response) {
		            // Populate the form fields with the data returned from server
		            var responseObject = JSON.parse(response).user[0]
		        	       	$('#Id').val(responseObject.Id),
                            $('#user_login').val(responseObject.user_login),
                            $('#provide_id').val(responseObject.provide_id)
		        });

        }
        else{
        	console.log("We are in delete>>>>>>>");
				var id = this.id
		        $.ajax({
		            url: 'http://localhost/php/Deal_market/public/api/users/userid/'+ id,
		            method: 'DELETE',
		            success:function(data, textStatus, jqXHR){
		            	console.log("Deletion status >>>>>")
		            	alert("You have successfully deleted");
		            },error:function(error){
		            	console.log("Some error occured >>>>",error);
		            }
		        })

        }
    });
});