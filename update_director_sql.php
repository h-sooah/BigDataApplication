<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "connectDB.php";

mysqli_query($mysqli, "ALTER TABLE DIRECTOR ENGINE = INNODB");

/* Turn autocommit OFF */
mysqli_autocommit($mysqli, false);

$name = $_GET["name"];


$sql = "UPDATE DIRECTOR
		SET directorSex = ?, directorAge = ?
		WHERE DIRECTOR.directorName = '" . $name . "'";

try {

	$stmt = mysqli_prepare($mysqli, $sql);
	mysqli_stmt_bind_param($stmt, "si", $directorSex, $directorAge);

	
	$directorSex = $_POST["directorSex"];
	$directorAge = (int) $_POST["directorAge"];

	mysqli_stmt_execute($stmt);
	mysqli_commit($mysqli);

	mysqli_autocommit($mysqli, true);
} catch (mysqli_sql_exception $exception) {
	mysqli_rollback($mysqli);

	throw $exception;
}

mysqli_stmt_close($stmt);
mysqli_close($mysqli);


header("Location: director_detail.php?name=" . $name);
exit();

?>