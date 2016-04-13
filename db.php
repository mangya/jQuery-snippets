<?php

/*** mysql hostname ***/
$hostname = 'localhost';

/*** mysql username ***/
$username = 'root';

/*** mysql password ***/
$password = '';

try {
		$dbh = new PDO("mysql:host=$hostname;dbname=mrg", $username, $password);
		
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }