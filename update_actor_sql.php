<?php

include "connectDB.php";

$name = $_GET["name"];


$sql = "UPDATE ACTOR
		SET actorSex = ?, actorAge = ?, actorHeight = ?, issueId = ?
		WHERE ACTOR.actorName = '" . $name . "'";

if ($stmt = mysqli_prepare($mysqli, $sql)) {
		mysqli_stmt_bind_param($stmt, "siii", $actorSex, $actorAge, $actorHeight, $issueId);

		
		$actorSex = $_POST["actorSex"];
		$actorAge = (int) $_POST["actorAge"];
		$actorHeight = (int) $_POST["actorHeight"];
		$issueId = (int) $_POST["issue"];


		if (!mysqli_stmt_execute($stmt)) {
			echo "ERROR: could not execute query: $sql. " .mysqli_error($mysqli);	
		}

	} else {
		echo "ERROR: could not prepare query: $sql. " .mysqli_error($mysqli);	
	}

mysqli_stmt_close($stmt);
mysqli_close($mysqli);


header("Location: actor_detail.php?name=" . $name);
exit();

?>