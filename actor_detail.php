<?php
	include "header.php";
?>


<?php

	include "connectDB.php";

	$sql = "SELECT *
			FROM ACTOR
			WHERE 
				ACTOR.actorName = ?";

	if ($stmt = mysqli_prepare($mysqli, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $name);

		$name = $_GET['name'];

		if (mysqli_stmt_execute($stmt)) {
 
			$res = mysqli_stmt_get_result($stmt);

			if ($res) {

				$row = mysqli_fetch_assoc($res);

				$actorId = $row['actorId'];
				$actorSex = $row['actorSex'];
				$actorAge = $row['actorAge'];
				$actorHeight = $row['actorHeight'];
				$issue = $row['issueId'];

				$image = "";
				if (strcmp($actorSex, "남성")) {
					$image = "./data/image/woman.png";
				}
				else {
					$image = "./data/image/man.png";
				}

				echo "<table><tr><td>";
				echo "<img src=" . $image . " width=200 height=200 /></td>";
				echo "<td><h1>" . $name . "</h1>";
				echo "<p>성별: " . $actorSex . "</p><p>나이: " . $actorAge . "세</p><p>키: ". $actorHeight . "cm</p>";
				echo "<p>논란: ";

				if ($issue == 1) {
					echo "없음</p></td></tr></table>";
				}
				else {
					$query = "SELECT issueContent
							  FROM ISSUE
							  WHERE ISSUE.issueId=" . $issue;
					$result = mysqli_query($mysqli, $query);
					if ($result) {
						$array = mysqli_fetch_array($result, MYSQLI_ASSOC);
						$content = $array['issueContent'];
						echo $content . "</p></div>";
					}
					else {
						printf("Could not retrieve records: %s\n", mysqli_error($mysqli));
					}
				}

				echo "<br/><hr id=\"line\" width=50%>";


			} else {
				echo "There is no result.";
			}


		} else {
			echo "ERROR: Could not execute query: $sql. " . mysqli_error($mysqli);
		}

	} else {
		echo "ERROR: Could not prepare query: $sql" . mysqli_error($mysqli);
	}


	echo "<h3>필모그래피</h3><div class=\"filmography\">";
	$sql = "SELECT posterPath, movieTitle
			FROM MOVIE_DETAIL
				INNER JOIN MOVIE_ACTOR
				ON MOVIE_DETAIL.movieId = MOVIE_ACTOR.movieId
			WHERE
				MOVIE_ACTOR.actorId=" . $actorId;

	$res = mysqli_query($mysqli, $sql);
	if ($res) {
		while ($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
			$poster = $newArray['posterPath'];
			$movieTitle = $newArray['movieTitle'];
			$url = 'movie_detail.php?title='.$movieTitle;
			echo "<article><img src=" . $poster . " width=200 height=300 onClick=\"location.href='" . $url . "'\" /></article>";
		}
		echo "</div>";
	} else {
		printf("Could not retrieve records: %s\n", mysqli_error($mysqli));
	}

	echo "<br/><br/>";
	echo "<div id=\"btn_group\">
        <button id=\"test_btn\" onClick=\"location.href='update_actor.php?name=" . $name . "'\">배우 정보 수정하기</button>
        <button id=\"test_btn\" onClick=\"location.href='delete_actor.php?actorId=" . $actorId . "'\">배우 정보 삭제하기</button>
    	</div></div>";

	mysqli_stmt_close($stmt);

	mysqli_free_result($res);

	mysqli_close($mysqli);

?>

</div>

</body>
</html>

<style>


	table {
		margin: 0 auto;
    	width: 50%;
    	margin-top: 3%;
	}

	td {
		width:200px; 
		padding-right:50px;  
		padding-left:50px; 
		text-align: left;
  		vertical-align: middle;
	}


	.filmography {
		width: 80%;
		margin: 0 auto;
		overflow: hidden;
		padding-top: 15px;
		padding-bottom: 5px;
		border-top-left-radius: 5px;
		border-bottom-left-radius: 5px;
		border-top-right-radius: 5px;
		border-bottom-right-radius: 5px;
		display: flex;
        justify-content: center;
	}

	article {
		float: left;
		margin-left: 1%;
		margin-right: 1%;
		margin-bottom: 0.5%;
	}

	#test_btn{
            border-top-left-radius: 5px;
			border-bottom-left-radius: 5px;
			border-top-right-radius: 5px;
			border-bottom-right-radius: 5px;
            margin-right:10px;
    }

    #btn_group {
    	display: flex;
		justify-content: center;
		margin-bottom:10px;
    }

    #btn_group button{
        border: 1px solid #F26C6C;
        background-color: rgba(0,0,0,0);
        color: #F26C6C;
        padding: 10px;
    }

    #btn_group button:hover{
        color:white;
        background-color: #F26C6C;
    }

    h3 {
    	text-align: center;
    }
</style>