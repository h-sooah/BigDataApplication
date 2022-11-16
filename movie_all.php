<?php
	include "header.php";
?>


<?php

	include "connectDB.php";

	/* 페이지 설정 */
	$list_num = 10; // 한 페이지 당 데이터 개수
	$page_num = 10; // 한 블럭 당 페이지 개수

	/* 현재 페이지 */
	$page = isset($_GET["page"])? $_GET["page"] : 1;

	/* 전체 데이터 수 */
	$sql = "SELECT count(movieId) as num
			FROM MOVIE_DETAIL";
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
	$sql = "SELECT movieId, movieTitle, posterPath, genres
			FROM MOVIE_DETAIL USE INDEX (movie_idx)
			ORDER BY movieTitle ASC
			LIMIT " . $start . ", " . $list_num;
	$res = mysqli_query($mysqli, $sql) or die ("Could not retrieve records: " . $mysqli_error($mysqli));
	$rows = mysqli_num_rows($res);

	echo "<div id=\"wrap\">";
	echo "<br/>";
	while ($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
			$movieId = $newArray['movieId'];
			$movieTitle = $newArray['movieTitle'];
			$posterPath = $newArray['posterPath'];
			$genres = $newArray['genres'];
			
			$url = "movie_detail.php?title=" . $movieTitle;
			
			
			echo "<article><img src=" . $posterPath . " width=150 height=200 onClick=\"location.href='" . $url . "'\"/>";

			echo "<h3 id='title'> <a href='" .$url . "'>" . $movieTitle . "</a></h3>";
			echo "<p id='genre'>[" . $genres . "]</p></article>";

		}
	echo "</div>";
?>



<div class="page">
	<ul class="pagination modal">

	<li><a href = "movie_all.php?page=1" class="first">처음 페이지</a></li>
	<?php

	/* 이전 페이지 */
	if ($page <= 1) {
	?>
	<li><a href = "movie_all.php?page=1" class="arrow left"><<<</a></li>
	<?php } else { ?>
	<li><a href = "movie_all.php?page=<?php echo ($page - 1); ?>" class="arrow left"><<<</a></li>
	<?php };
	?>

	<?php
	/* 페이지 번호 출력 */
	for ($print_page = $s_pageNum; $print_page <= $e_pageNum; $print_page++) {
		if ($print_page == $page) { ?>
			<li><a href = "movie_all.php?page=<?php echo $print_page; ?>" class="cur_num"><?php echo $print_page; ?></a></li>
		<?php }
		else { ?>
			<li><a href = "movie_all.php?page=<?php echo $print_page; ?>" class="num"><?php echo $print_page; ?></a></li>
		<?php }
	}
	?>

	<?php
	/* 다음 페이지 */
	if ($page >= $total_page) {
		?>
		<li><a href = "movie_all.php?page=<?php echo $total_page; ?>" class="arrow right">>>></a></li>
		<?php } else{ ?>
		<li><a href = "movie_all.php?page=<?php echo ($page+1); ?>" class="arrow right">>>></a></li>
		<?php }; 
	?>
	<li><a href = "movie_all.php?page=<?php echo $total_page; ?>" class="last">마지막 페이지</a></li>

	</ul>
</div>
<br/>

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
		width: 850px;
		margin: 0 auto;
		overflow: hidden;
		padding-top: 12px;
	}

	article {
		float: left;
		margin-left: 1%;
		margin-right: 1%;
		margin-bottom: 0.5%;
		width: 150px;
		height: 270px;
	}

	img {
		display: block;
	}

	#title {
		margin-top: 10px;
		text-align: center;
	}

	#genre {
		color: gray;
		text-align: center;
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


</style>

