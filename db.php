<?php

/*** mysql hostname ***/
$hostname = 'localhost';

/*** mysql username ***/
$username = 'root';

/*** mysql password ***/
$password = '';

	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=db_itm_elearning_materials", $username, $password);
    }
	catch(PDOException $e)
    {
		echo $e->getMessage();
    }