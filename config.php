<?php

	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'shimata');
	define('DB_NAME', 'crud1');

	$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if($link === false )
	{
		die("ERROR: Could not connect to MYSQL. ". mysqli_connect_error());
	} 