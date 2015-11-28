<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Deals.com</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		
	</style>
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script src="js/login.js"></script>
	<script src="js/create_deals.js"></script>
</head>
<body>
	<?php
	if(isset($_GET["page"])){
		$page = $_GET["page"].'.php';
		if(file_exists($page))
    		include $page;
    	else 
    		echo "File not found!";
	}else{
		include 'login.php';
	}
?>
</body>
</html>