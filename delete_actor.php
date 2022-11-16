<?php

include "connectDB.php";

$actorId = $_REQUEST["actorId"];

$sql = "DELETE
		FROM MOVIE_ACTOR
		WHERE MOVIE_ACTOR.actorId = " . $actorId;

$res = mysqli_query($mysqli, $sql);

$sql = "DELETE
		FROM ACTOR
		WHERE ACTOR.actorId = " . $actorId;

$res = mysqli_query($mysqli, $sql);

mysqli_close($mysqli);

header("Location: actor_all.php");
exit();
?>