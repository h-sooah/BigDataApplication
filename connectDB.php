<?php

header('Content-Type: text/html; charset=UTF-8');

	$mysqli = mysqli_connect("localhost", "team04", "team04", "team04");
	if ($mysqli == false) {
		die("ERROR: Could not connect. " .mysqli_connect_error());
	}
?>