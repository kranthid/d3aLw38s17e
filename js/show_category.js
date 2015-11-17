$(document).ready(function() {
   var table = $('#deals').DataTable({
        "ajax": {
        			"url":"http://localhost/php/Deal_market/public/api/deals/dealcategories/all",
        			"dataSrc": "dealCategories"
        },
        "columns": [
            { "data": "Id" },
            { "data": "category_name" }
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
                            "category_name": $('#cname').val(),
                            "status": $('#status').val()
                        };
            var phpUpdateUrl = "http://localhost/php/Deal_market/public/api/deals/category/"+id
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
		            url: 'http://localhost/php/Deal_market/public/api/deals/category/' + id,
		            method: 'GET'
		        }).success(function(response) {
		            // Populate the form fields with the data returned from server
		            var responseObject = JSON.parse(response).dealCategories[0]
		        	       	$('#Id').val(responseObject.Id),
                            $('#cname').val(responseObject.category_name),
                            $('#status').val(responseObject.status)
		        });

        }
        else{
        	console.log("We are in delete>>>>>>>");
				var id = this.id
		        $.ajax({
		            url: 'http://localhost/php/Deal_market/public/api/deals/category/'+ id,
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