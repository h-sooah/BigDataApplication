<?php

include "connectDB.php";

$sql = "INSERT INTO ACTOR (actorName, actorSex, actorAge, actorHeight, issueId)
		VALUES (?, ?, ?, ?, ?)";

if ($stmt = mysqli_prepare($mysqli, $sql)) {
		mysqli_stmt_bind_param($stmt, "ssiii", $actorName, $actorSex, $actorAge, $actorHeight, $issueId);

		
		$actorName = $_POST["actorName"];
		$actorSex = $_POST["actorSex"];
		$actorAge = (int) $_POST["actorAge"];
		$actorHeight = (int) $_POST["actorHeight"];
		$issueId = (int) $_POST["issue"];


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


header("Location: actor_detail.php?name=" . $actorName);
exit();


?>