<?php
	include "header.php";
?>

<h1>SEARCH MOVIE</h1>
<div class=searchMovie>
	<form action="search_result.php" method=POST>
	<br/>
	<input type=text name="movieTitle">
	<br/>
	<article class="getResult">
	<input type=submit value="검색하기" onClick="location.href='search_result.php'">
	</article>
	</form>
</div>


</div>
</body>
</html>



<style>

	.searchMovie {
		background-color: white;
		padding-top: 1px;
		padding-bottom: 10px;
		width: 600px;
		margin: 0 auto;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.getResult {
		display: flex;
		justify-content: center;
	}

	h1 {
		text-align: center;
	}

	input[type=text] {
	  width: 500px;
	  height: 32px;
	  font-size: 15px;
	  border: 0;
	  border-radius: 15px;
	  outline: none;
	  padding-left: 10px;
	  background-color: rgb(233, 233, 233);

	}

	input[type=submit] {
            border-top-left-radius: 5px;
			border-bottom-left-radius: 5px;
			border-top-right-radius: 5px;
			border-bottom-right-radius: 5px;
            border: 1px solid #F26C6C;
	        background-color: rgba(0,0,0,0);
	        color: #F26C6C;
	        padding: 5px;
	        margin-top: 15px;
	        width: 300px;
	    }


    input[type=submit]:hover{
        color:white;
        background-color: #F26C6C;
    }
	
</style>