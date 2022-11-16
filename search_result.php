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

	$sql = "SELECT count(movieId) as num
			FROM 
				MOVIE_DETAIL
			WHERE 
				MOVIE_DETAIL.movieTitle LIKE ?";


	/* 전체 데이터 수 */
	if ($stmt = mysqli_prepare($mysqli, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $title);

		$title = "%" . $_POST["movieTitle"] . "%";

		if (mysqli_stmt_execute($stmt)) {

			$result = mysqli_stmt_get_result($stmt);
			if ( $result ) {
    			$row = mysqli_fetch_assoc($result);
    			$num = $row['num'];
			}	

		}

	} else {
		echo "ERROR: could not prepare query: $sql. " .mysqli_error($mysqli);
	}


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


	$sql = "SELECT movieTitle, posterPath
			FROM 
				MOVIE_DETAIL
			USE INDEX (movie_idx)
			WHERE 
				MOVIE_DETAIL.movieTitle LIKE ?";



	if ($stmt = mysqli_prepare($mysqli, $sql)) {
		mysqli_stmt_bind_param($stmt, "s", $title);

		$title = "%" . $_REQUEST["movieTitle"] . "%";

		if (mysqli_stmt_execute($stmt)) {

			$result = mysqli_stmt_get_result($stmt);
			if ( $result ) {
    		
    		echo "<div id=\"wrap\">";
			echo "<h1>검색 결과</h1>";
			while ($row = mysqli_fetch_assoc($result)) {

    			$poster = $row['posterPath'];
    			$movieTitle = $row['movieTitle'];

    			$url = 'movie_detail.php?title='.$movieTitle;
    			echo "<article><img src='" . $poster . "' width=150 height=200 onClick=\"location.href='" . $url . "'\" /></article>";
				}
			}	
			echo "</div>";
		}

	} else {
		echo "ERROR: could not prepare query: $sql. " .mysqli_error($mysqli);
	}


?>

<div class="page">
	<ul class="pagination modal">

	<li><a href = "search_result.php?movieTitle=<?php echo $title ?>&page=1" class="first">처음 페이지</a></li>

	<?php

	/* 이전 페이지 */
	if ($page <= 1) {
	?>
	<li><a href = "search_result.php?movieTitle=<?php echo $title ?>&page=1" class="arrow left"><<<</a></li>
	<?php } else { ?>
	<li><a href = "search_result.php?movieTitle=<?php echo $title ?>&page=<?php echo ($page - 1); ?>" class="arrow left"><<<</a></li>
	<?php };
	?>

	<?php
	/* 페이지 번호 출력 */
	for ($print_page = $s_pageNum; $print_page <= $e_pageNum; $print_page++) {
		if ($print_page == $page) { ?>
			<li><a href = "search_result.php?movieTitle=<?php echo $title ?>&page=<?php echo $print_page; ?>" class="cur_num"><?php echo $print_page; ?></a></li>
		<?php }
		else { ?>
			<li><a href = "search_result.php?movieTitle=<?php echo $title ?>&page=<?php echo $print_page; ?>" class="num"><?php echo $print_page; ?></a></li>
		<?php }
	}
	?>

	<?php
	/* 다음 페이지 */
	if ($page >= $total_page) {
		?>
		<li><a href = "search_result.php?movieTitle=<?php echo $title ?>&page=<?php echo $total_page; ?>" class="arrow right">>>></a></li>
		<?php } else{ ?>
		<li><a href = "search_result.php?movieTitle=<?php echo $title ?>&page=<?php echo ($page+1); ?>" class="arrow right">>>></a></li>
		<?php }; 
	?>
	<li><a href = "search_result.php?movieTitle=<?php echo $title ?>&page=<?php echo $total_page; ?>" class="last">마지막 페이지</a></li>

	</ul>
</div>

<?php
	mysqli_free_result($result);
	mysqli_stmt_close($stmt);
	mysqli_close($mysqli);
?>

</div>
</body>
</html>


<style>


	#wrap {
		width: 850px;
		margin: 0 auto;
		overflow: hidden;
		padding-top: 12px;
	}

	h1 {
		text-align: center;
	}

	article {
		float: left;
		margin-left: 1%;
		margin-right: 1%;
		margin-bottom: 0.5%;
	}

	img {
		display: block;
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