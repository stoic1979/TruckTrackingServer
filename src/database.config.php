<?php

	define('HOST','localhost');
	define('USER','weavebyt_navi');
	define('PASSWORD', 'st01c');
	define('DBNAME','weavebyt_trucktracking');

	//conencting to database
	$con=mysql_connect(HOST, USER, PASSWORD);
	if(!$con){
		echo"connection not created";
	}
	
	//selecting database
	$db=mysql_select_db(DBNAME);
		
	if(!$db){
		die("<b>ERROR: Failed to connect to database. <br>Can't continure further...</b>");
	}
?>
