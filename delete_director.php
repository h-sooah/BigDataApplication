<?php

include "connectDB.php";

$directorId = $_REQUEST["directorId"];

$sql = "DELETE
		FROM MOVIE_DIRECTOR
		WHERE MOVIE_DIRECTOR.directorId = " . $directorId;

$res = mysqli_query($mysqli, $sql);

$sql = "DELETE
		FROM DIRECTOR
		WHERE DIRECTOR.directorId = " . $directorId;

$res = mysqli_query($mysqli, $sql);

mysqli_close($mysqli);

header("Location: director_all.php");
exit();
?>