<?php
	include "header.php";
?>


<?php

	include "connectDB.php";

	$sql = "SELECT *
			FROM MOVIE_DETAIL
			WHERE 
				MOVIE_DETAIL.movieTitle = ?";

	if ($stmt = mysqli_prepare($mysqli, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $title);

		$title = $_GET['title'];

		if (mysqli_stmt_execute($stmt)) {
 
			$res = mysqli_stmt_get_result($stmt);

			if ($res) {

				$row = mysqli_fetch_assoc($res);

				$movieId = $row['movieId'];
				$poster = $row['posterPath'];
				$title = $row['movieTitle'];
				$runtime = $row['runningTime'];
				$genres = $row['genres'];
				$age = $row['age'];
				$released = $row['releasedDate'];
				$budget = $row['budget'];
				$revenue = $row['revenue'];
				$story = $row['story'];

				echo "<br/>";	
				echo "<h1 align='center'>" . $title . "</h1>";
				#echo "<div class=\"img-container-size\"><div class=\"img-box-center\"><img src=" . $poster . " width=250 height=400 class=\"img\"/></div></div>";
				echo "<table><tr style=\"vertical-align:top\"><td><img src=" . $poster . " width=300 height=400 align=\"left\"/></td>";


				echo "<td><article class='info'><h4>| 상영시간 : " . $runtime . "분</h4><h4>| 연령제한 : " . $age . "</h4><h4>| 장르 : ". $genres . "</h4><h4>| 개봉일 : " . $released . "</h4><h4><h4>| 감독 : ";


			} else {
				echo "There is no result.";
			}


		} else {
			echo "ERROR: Could not execute query: $sql. " . mysqli_error($mysqli);
		}

	} else {
		echo "ERROR: Could not prepare query: $sql" . mysqli_error($mysqli);
	}

	mysqli_stmt_close($stmt);


	$sql = "SELECT directorName
			FROM DIRECTOR
				INNER JOIN MOVIE_DIRECTOR
				ON DIRECTOR.directorId = MOVIE_DIRECTOR.directorId
			WHERE
				MOVIE_DIRECTOR.movieId=" . $movieId;

	$res = mysqli_query($mysqli, $sql);
	if ($res) {
		while ($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
			$directorName = $newArray['directorName'];
			echo " <a href=\"director_detail.php?name=" . $directorName . "\">" . $directorName . "</a>";
		}
		echo "</h4>";
	} else {
		printf("Could not retrieve records: %s\n", mysqli_error($mysqli));
	}


	echo "<h4>| 배우 : ";
	$sql = "SELECT actorName
			FROM ACTOR
				INNER JOIN MOVIE_ACTOR
				ON ACTOR.actorId = MOVIE_ACTOR.actorId
			WHERE
				MOVIE_ACTOR.movieId=" . $movieId;

	$res = mysqli_query($mysqli, $sql);
	if ($res) {
		while ($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
			$actorName = $newArray['actorName'];
			echo " <a href=\"actor_detail.php?name=" . $actorName . "\">" . $actorName . "</a>";
		}
		echo "</h4></article></td>";
		echo "<td><div id=\"overview\">[줄거리]<br/>" . $story . "</div></td>";

		#echo "<hr id=\"line\">";

		echo "<td><script src=\"https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js\"></script>";
		echo "<canvas id=\"bar-chart\" width=\"600\" height=\"300\"></canvas>";
		echo "<script>
				new Chart(document.getElementById(\"bar-chart\"), {
					type: 'bar',
						data: {
						labels: [\"budget\", \"revenue\"],
						datasets: [
						{
  							label: \"Money (won)\",
  								backgroundColor: [\"#3e95cd\", \"#8e5ea2\"],
  								data: [" . $budget . ", " . $revenue . "]
						}
						]
				},
				options: {
					responsive: false,
						legend: { display: false },
						title: {
		 					display: true,
						text: 'BUDGET vs REVENUE'
							}
					}
				});
			</script>";
		echo "<p>- 예산: ₩" . $budget . "</p><p>- 수익: ₩" . "$revenue </p>";
	} else {
		printf("Could not retrieve records: %s\n", mysqli_error($mysqli));
	}

	echo "</td></tr></table>";
	echo "<br/><hr><br/><br/>";
	echo "<h3>Review</h3><br/>";

	echo "<div>";
	$sql = "SELECT
				COALESCE(FLOOR(userAge/10)*10,\"ALL age\") as ages,
				COALESCE(userSex,\"ALL sex\") as userSex, 
				COALESCE(AVG(r.rate),0) as avgRate, 
				COUNT(r.rate) as num
			FROM
				(SELECT userId, userAge, userSex FROM USER) as u
				LEFT join
				(SELECT userId, rate, movieId FROM REVIEW WHERE REVIEW.movieID = " . $movieId . ") as r
			ON u.userId = r.userId
			GROUP BY ages, userSex WITH ROLLUP
			";

	$res = mysqli_query($mysqli, $sql);
	if ($res) {
		echo "<div class=\"left\"><table id=\"statistics\"><tr><td>연령대</td><td>성별</td><td>평균 평점</td><td>관람 수</td></tr>";
		while ($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
			$age = $newArray['ages'];
			$sex = $newArray['userSex'];
			$avgRate = $newArray['avgRate'];
			$count = $newArray['num'];

			echo "<tr><td>" . $age . "</td><td>" . $sex . "</td><td>" . $avgRate . "</td><td>" . $count . "</td></tr>";
		}
		echo "</table></div>";
	}

	echo "<div class=\"right\">";
	$sql = "SELECT
				watchDate, rate, content, userSex, id
			FROM 
				REVIEW
				INNER JOIN USER
			ON REVIEW.userId = USER.userId
			WHERE
				REVIEW.movieId = " . $movieId;

	$res = mysqli_query($mysqli, $sql);
	if ($res) {
	
		while ($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
			$watchDate = $newArray['watchDate'];
			$rate = $newArray['rate'];
			$content = $newArray['content'];
			$userSex = $newArray['userSex'];
			$id = $newArray['id'];

			$image = "";
			if (strcmp($userSex, "남성")) {
				$image = "./data/image/woman.png";
			}
			else {
				$image = "./data/image/man.png";
			}

			echo "<div id=\"user-review\">";
			echo "<article><img src=" . $image . " width=50 height=50/></article>";
			echo "<article><h4>" . $id . "</h4></article>";
			echo "<article id=\"review-content\">시청일: " . $watchDate . "<br/>평점: ";
			for ($i=1; $i <= $rate; $i++) {
				echo "⭐";
			}
			echo "<br/>" . $content . "</article>";
			echo "</div>";

		}

	}
	echo "</div></div>";


	echo "<div id=\"btn_group\">
        <button id=\"test_btn\" onClick=\"location.href='create_review.php?title=" . $title . "'\">리뷰 작성하기</button>
    	</div>";


	mysqli_free_result($res);

	mysqli_close($mysqli);

?>


</div>
</body>
</html>


<style>

	* {
		margin: 0;
		padding: 0;
	}

	/*
	#wrap {
		width: 90%;
		margin-top: 100px;
		padding-top: 12px;
		border: 1px solid red;
	}
	*/

	
	#statistics {
	    width: 500px;
	    border-top: 1px solid #444444;
	    border-collapse: collapse;
	    margin-left: 10px;
	  }

	#statistics td {
	    padding: 8px;
	    text-align: center;
	}

	#statistics tr {
	   border-bottom: 1px solid #444444;
	}

	#statistics tr:nth-child(1) {
	   background-color: #112D4E;
	   color: white;
	}

	#statistics tr:nth-child(3n+4) {
	   background-color: #DBE2EF;
	}

	#statistics tr:nth-last-child(1) {
	   background-color: #3F72AF;
	   color: white;
	}

	#overview {
		font-size: 12px;
		height: 300px;
	}

	table {
		margin-left:auto; 
    	margin-right:auto;
	}

	td {
		width:200px; 
		padding-right:30px;  
		padding-left:30px; 
  		vertical-align : middle;
	}

	article {
		float: left;
		margin-left: 1%;
		margin-right: 1%;
		margin-bottom: 0.5%;
	}

	.info h4 {
		line-height: 50px;
	}

	/*
	#review {
		width: 100%;
		margin: 0 auto;
		margin-top: 10px;
		border: 2px solid green;
	}
	*/
	div.left {
		width: 35%;
		float:left;
		margin-left: 20px;
	}

	div.right {
		width: 60%;
		float: right;
		margin-right: 20px;
	}

	#user-review {
		width: 420px;
		height: 100px;
		margin: 0 auto;
		overflow: auto;
		padding-top: 15px;
		padding-bottom: 15px;
		border: 1px solid #EAEAEA;
		background-color: #EAEAEA;
		border-top-left-radius: 5px;
		border-bottom-left-radius: 5px;
		border-top-right-radius: 5px;
		border-bottom-right-radius: 5px;
		float: left;
		margin-left: 20px;
		margin-bottom: 20px;
		display: flex;
		align-items: center;
	}

	#review-content {
		margin-left: 20px;
		line-height: 22px;
	}

	#test_btn{
            border-top-left-radius: 5px;
			border-bottom-left-radius: 5px;
			border-top-right-radius: 5px;
			border-bottom-right-radius: 5px;
            margin-right:-4px;
    }

    #btn_group {
    	width: 100%;
    	display: flex;
		justify-content: center;
		margin-bottom: 15px;
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


	/*
	.img-container-size{
        position: relative;
        width: 100%; #화면 크기와 동일하게 맞춰준다. 전체크기를
        height: 600px; #높이 고정
        overflow: hidden; #이미지의 크기가 크면 나머지 부분 삭제
    }

    .img-box-center{
        position: absolute;
        #화면 감소시 이미지 크기는 right, left 포지션값에 의해 결정된다.
        #포지션값을 -200% 시킴으로써 화면이 줄어들어도 이미지 크기가 감소하는 right, left 값이 되지 않음
        top: 0;
        right: -200%;
        bottom: 0;
        left: -200%;
    }

    
    .img{
        display: block;
        margin: 0 auto;
    }
    

    @media screen and (max-width: 1000px) {
        .img-box-center {
        text-align: left;
        left: -255px;
        }
    }
    */

	h3, p {
 		text-align: center;
	}

	canvas {
		align-items: center;
	}

	a:link {
 		color : black;
	}

	a:visited {
  		color : black;
	}

	a:hover {
  		color : #F26C6C;
	}

	a:active {
  		color : #F26C6C;
	}


</style>