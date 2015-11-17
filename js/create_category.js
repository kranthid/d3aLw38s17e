	function clearFunction(){
	
		document.getElementById("createPanel").reset();
	}
	function createCategory(){
            var objData = {
                            "Id": $('#Id').val(),
                            "category_name": $('#cname').val(),
                            "status": $('#status').val()
                        };
            var phpPostUrl = "http://localhost/php/Deal_market/public/api/deals/category/create"
            $.ajax({
                url : phpPostUrl,
                type: "POST",
                data : JSON.stringify(objData),
                success: function(data, textStatus, jqXHR)
                {
                    console.log("Data sent to the server >>>>>>>>>>>>>>>>",data)
                    alert("You have successfully created");
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    console.log("Error while sending data to the server>>>>>>>>>>>>>>>>",errorThrown)
                }
            });

	}