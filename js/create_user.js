	function clearFunction(){
	
		document.getElementById("createPanel").reset();
	}
	function createUser(){
            var objData = {
                            "Id": $('#Id').val(),
                            "user_login": $('#ulogin').val(),
                            "provide_id": $('#provide_id').val()
                        };
            var phpPostUrl = "http://localhost/php/Deal_market/public/api/users/create"
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