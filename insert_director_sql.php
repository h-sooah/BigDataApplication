<?php

include "connectDB.php";

$sql = "INSERT INTO DIRECTOR (directorName, directorSex, directorAge)
		VALUES (?, ?, ?)";

if ($stmt = mysqli_prepare($mysqli, $sql)) {
		mysqli_stmt_bind_param($stmt, "ssi", $directorName, $directorSex, $directorAge);

		
		$directorName = $_POST["directorName"];
		$directorSex = $_POST["directorSex"];
		$directorAge = (int) $_POST["directorAge"];

		if (mysqli_stmt_execute($stmt)) {
			echo "insert SUCCESS\n";
		}
		else {
			echo "ERROR: could not execute query: $sql. " .mysqli_error($mysqli);	
		}

	} else {
		echo "ERROR: could not prepare query: $sql. " .mysqli_error($mysqli);	
	}

mysqli_stmt_close($stmt);
mysqli_close($mysqli);


header("Location: director_detail.php?name=" . $directorName);
exit();


?>