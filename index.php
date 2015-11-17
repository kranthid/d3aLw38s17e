<?php
	//include 'home.php';
	if(isset($_GET["page"])){
		if($_GET["page"] == "login"){
			include 'login.php';
		}	
	}else{
		echo 'page required';
	}
?>