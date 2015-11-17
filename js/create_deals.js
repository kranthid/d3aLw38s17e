    function clearFunction(){
	
		document.getElementById("createPanel").reset();
	}
	function createDeal(){
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
            var phpPostUrl = "http://localhost/php/Deal_market/public/api/deal/create"
            $.ajax({
                url : phpPostUrl,
                type: "POST",
                data : JSON.stringify(objData),
                success: function(data, textStatus, jqXHR)
                {
                    console.log("Data sent to the server >>>>>>>>>>>>>>>>",data);
                    alert("You have created the deal successfully")
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    console.log("Error while sending data to the server>>>>>>>>>>>>>>>>",errorThrown)
                }
            });

	}