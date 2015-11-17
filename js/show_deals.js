$(document).ready(function() {
   var table = $('#deals').DataTable({
        "ajax": {
        			"url":"http://localhost/php/Deal_market/public/api/deals/all",
        			"dataSrc": "deal"
        },
        "columns": [
            { "data": "Id" },
            { "data": "title" }
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
                            "user_id": $('#uid').val(),
                            "deal_url":  $('#url').val(),
                            "title":  $('#title').val(),
                            "deal_price":  $('#deal_price').val(),
                            "deal_availablity":  $('#deal_availablity').val(),
                            "city_postcode": $('#city_postcode').val(),
                            "deal_category": $('#category').val(),
                            "deal_topic": $('#topic').val(),
                            "discount": $('#discount').val(),
                            "discount_code": $('#discount_code').val(),
                            "detail": $('#detail').val(),
                            "prize": $('#prize').val(),
                            "period": $('#period').val(),
                            "deal_image": $('#image').val(),
                            "deal_image_url":$('#image_url').val(),
                            "tags": $('#tags').val(),
                            "deal_rule": $('#rule').val(),
                            "link_to_rule": $('#link_rule').val(),
                            "apply_to": $('#apply_to').val(),
                            "report": $('#report').val(),
                            "deal_like": $('#like').val(),
                            "deal_dislike": $('#dislike').val(),
                            "start_date": $('#sdate').val(),
                            "end_date": $('#edate').val(),
                            "status": $('#status').val()
                        };
            var phpUpdateUrl = "http://localhost/php/Deal_market/public/api/deals/dealId/"+id
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
})
 	$('#deals').on('click', 'a', function () {
 		console.log(">>>>>>>>>>>>>>>>..")
        if( $(this).attr("class").indexOf('update') != -1){
        	console.log("We are in update>>>>>>>>");
        		$('#basicModal').modal('show');
        		var id = this.id
		        $.ajax({
		            url: 'http://localhost/php/Deal_market/public/api/deals/dealId/' + id,
		            method: 'GET'
		        }).success(function(response) {
		            // Populate the form fields with the data returned from server
		            var responseObject = JSON.parse(response).deal[0]
		        	       	$('#Id').val(responseObject.Id),
                            $('#uid').val(responseObject.user_id),
                            $('#url').val(responseObject.deal_url),
                            $('#title').val(responseObject.title),
                            $('#deal_price').val(responseObject.deal_price),
                            $('#deal_availablity').val(responseObject.deal_availablity),
                            $('#city_postcode').val(responseObject.city_postcode),
                            $('#category').val(responseObject.deal_category),
                            $('#topic').val(responseObject.deal_topic),
                            $('#discount').val(responseObject.discount),
                            $('#discount_code').val(responseObject.discount_code),
                            $('#detail').val(responseObject.detail),
                            $('#prize').val(responseObject.prize),
                            $('#period').val(responseObject.period),
                            $('#image').val(responseObject.deal_image),
                            $('#image_url').val(responseObject.deal_image_url),
                            $('#tags').val(responseObject.tags),
                            $('#rule').val(responseObject.deal_rule),
                            $('#link_rule').val(responseObject.link_to_rule),
                            $('#apply_to').val(responseObject.apply_to),
                            $('#report').val(responseObject.report),
                            $('#like').val(responseObject.deal_like),
                            $('#dislike').val(responseObject.deal_dislike),
                            $('#sdate').val(responseObject.start_date),
                            $('#edate').val(responseObject.end_date),
                            $('#status').val(responseObject.status)    
		        });

        }
        else{
        	console.log("We are in delete>>>>>>>");
				var id = this.id
		        $.ajax({
		            url: 'http://localhost/php/Deal_market/public/api/deals/dealId/'+ id,
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