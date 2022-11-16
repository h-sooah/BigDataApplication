<?php
	include "header.php";
?>


<?php

	include "connectDB.php";

	/* 페이지 설정 */
	$list_num = 9; // 한 페이지 당 데이터 개수
	$page_num = 10; // 한 블럭 당 페이지 개수

	/* 현재 페이지 */
	$page = isset($_GET["page"])? $_GET["page"] : 1;

	/* 전체 데이터 수 */
	$sql = "SELECT count(directorId) as num
			FROM DIRECTOR";
	$res = mysqli_query($mysqli, $sql) or die ("Could not retrieve records: " . $mysqli_error($mysqli));
	$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
	$num = $row['num'];

	/* 전체 페이지 수 = 전체 데이터 / 페이지 당 데이터 수 */
	$total_page = ceil ($num / $list_num);

	/* 전체 블럭 수 = 전체 페이지 수 / 블럭 당 페이지 수 */
	$total_block = ceil ($total_page / $page_num);

	/* 현재 블럭 번호 = 현재 페이지 번호 / 블럭 당 페이지 수 */
	$now_block = ceil ($page / $page_num);

	/* 블럭 당 시작 페이지 번호 = (해당 글의 블럭 번호 - 1) * 블럭 당 페이지 수 + 1 */
	$s_pageNum = ($now_block - 1) * $page_num + 1;
	// 데이터가 0개인 경우
	if ($s_pageNum <= 0) {
		$s_pageNum = 1;
	}

	/* 블럭 당 마지막 페이지 번호 = 현재 블럭 번호 * 블럭 당 페이지 수 */
	$e_pageNum = $now_block * $page_num;
	// 마지막 번호가 전체 페이지 수를 넘지 않도록
	if ($e_pageNum > $total_page) {
		$e_pageNum = $total_page;
	}


	/* 시작 번호 = (현재 페이지 번호 - 1) * 페이지 당 보여질 데이터 수 */
	$start = ($page - 1) * $list_num;


	// 현재 페이지 쿼리
	$sql = "SELECT directorId, directorName, directorSex
			FROM DIRECTOR
			ORDER BY directorName ASC
			LIMIT " . $start . ", " . $list_num;
	$res = mysqli_query($mysqli, $sql) or die ("Could not retrieve records: " . $mysqli_error($mysqli));
	$rows = mysqli_num_rows($res);

	echo "<div id=\"btn_group\">
        <button id=\"test_btn\" onClick=\"location.href='insert_director.php'\">감독 추가하기</button>
    	</div>";

	echo "<div id=\"wrap\">";
	while ($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
			$directorId = $newArray['directorId'];
			$directorName = $newArray['directorName'];
			$directorSex = $newArray['directorSex'];
			
			$image = "";
			if (!strcmp($directorSex, "여성")) {
				$image = "./data/image/woman.png";
			}
			else {
				$image = "./data/image/man.png";
			}

			echo "<div id=\"detail\">";
			echo "<article id=\"img\"><img src=" . $image . " width=100 height=100/></article>";

			$url = "director_detail.php?name=" . $directorName;
			echo "<article id=\"info\"><h3><a href=" . $url . ">" . $directorName . "</h3>";

			$query = "SELECT movieTitle
					  FROM MOVIE_DETAIL
					  	INNER JOIN MOVIE_DIRECTOR
					  	ON MOVIE_DETAIL.movieId = MOVIE_DIRECTOR.movieId
					  WHERE
					  	MOVIE_DIRECTOR.directorId = " . $directorId;
			$result = mysqli_query($mysqli, $query);
			if ($result) {
				while ($array = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					$movieTitle = $array['movieTitle'];
					echo "| <a href='movie_detail.php?title=" .$movieTitle . "'>" .$movieTitle . "</a> "; 
				}
				echo "<br/></article>";
			}
			echo "</div>";

		}
	echo "</div>";
?>

<div class="page">
	<ul class="pagination modal">

	<li><a href = "director_all.php?page=1" class="first">처음 페이지</a></li>
	<?php

	/* 이전 페이지 */
	if ($page <= 1) {
	?>
	<li><a href = "director_all.php?page=1" class="arrow left"><<<</a></li>
	<?php } else { ?>
	<li><a href = "director_all.php?page=<?php echo ($page - 1); ?>" class="arrow left"><<<</a></li>
	<?php };
	?>

	<?php
	/* 페이지 번호 출력 */
	for ($print_page = $s_pageNum; $print_page <= $e_pageNum; $print_page++) {
		if ($print_page == $page) { ?>
			<li><a href = "director_all.php?page=<?php echo $print_page; ?>" class="cur_num"><?php echo $print_page; ?></a></li>
		<?php }
		else { ?>
			<li><a href = "director_all.php?page=<?php echo $print_page; ?>" class="num"><?php echo $print_page; ?></a></li>
		<?php }
	}
	?>

	<?php
	/* 다음 페이지 */
	if ($page >= $total_page) {
		?>
		<li><a href = "director_all.php?page=<?php echo $total_page; ?>" class="arrow right">>>></a></li>
		<?php } else{ ?>
		<li><a href = "director_all.php?page=<?php echo ($page+1); ?>" class="arrow right">>>></a></li>
		<?php }; 
	?>
	<li><a href = "director_all.php?page=<?php echo $total_page; ?>" class="last">마지막 페이지</a></li>

	</ul>
</div>

<?php
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

	#wrap {
		width: 1300px;
		margin: 30px auto;
		overflow: hidden;
		padding-top: 20px;
	}

	img {
		display: block;
	}

	article {
		margin-left: 5px;
	}

	#img {
		width: 100px;
		top: 50%;
		transfrom: translate(0, -50%);
	}

	#info {
		margin-left: 10px;
		text-align: left;
		top: 50%;
		transfrom: translate(0, -50%);
		line-height: 30px;
	}

	#detail {
		width: 400px;
		margin: 0 auto;
		overflow: hidden;
		padding-top: 10px;
		padding-bottom: 10px;
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

	.page {
		text-align: center;
		width: 850px;
		margin: 0 auto;
		overflow: hidden;
	}

	.pagination {
		list-style: none;
		display: inline-block;
		padding: 0;
		margin-top: 20px;
	}

	.pagination li {
		display: inline;
		text-align: center;
	}

	.pagination a {
		float: left;
		display: block;
		font-size: 14px;
		text-decoration: none;
		padding: 5px 12px;
		color: black;
		line-height: 1.5;
	}

	.first {
		margin-right: 15px;
	}

	.last {
		margin-left: 15px;
	}

	.first:hover, .last:hover, .left.hover, .right:hover {
		color: #F26C6C;
	}

	.pagination a.active {
		cursor: default;
		color: #F26C6C;
	}

	.pagination a:active {
		outline: none;
	}

	.modal .num {
		margin-left: 3px;
		padding: 0;
		width: 30px;
		height: 30px;
		line-height: 30px;
		-moz-border-radius: 100%;
		-webkit-border-radius: 100%;
		border-radius: 100%;
	}

	.modal .cur_num {
		margin-left: 3px;
		padding: 0;
		width: 30px;
		height: 30px;
		line-height: 30px;
		-moz-border-radius: 100%;
		-webkit-border-radius: 100%;
		border-radius: 100%;
		background-color: #F26C6C;
		color: white;
	}

	.modal .num:hover {
		background-color: #FFE5E5;
		color: black;
	}

	.modal .num.active, .modal .num:active {
		background-color: #FFE5E5;
		cursor: pointer;
	}

	.arrow-left {
		width: 0;
		height: 0;
		border-top: 10px solid transparent;
		border-bottom: 10px solid transparent;
		border-right: 10px solid #F26C6C;
	}

	#test_btn{
            border-top-left-radius: 5px;
			border-bottom-left-radius: 5px;
			border-top-right-radius: 5px;
			border-bottom-right-radius: 5px;
            margin-right:10px;
    }

    #btn_group {
    	width: 1300px;
    	margin-top: 20px;
    	margin-left: 130px;
    	float: left;
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

</style>

