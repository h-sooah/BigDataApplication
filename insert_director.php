<?php
	include "header.php";
?>

<?php

	include "connectDB.php";

?>

<h1 align="center"> 감독 추가하기 </h1>
<div class=insertDirectorInfo>
	<form action="insert_director_sql.php" method=POST>
		<br/>
		이름
		<input type=text name="directorName">
		<br/><br/>
		성별
		<select name="directorSex">
			<option value="남성"> 남성 </option>
			<option value="여성" selected> 여성 </option>
		</select>
		<br/><br/>
		나이
		<input type=number name="directorAge">
		<br/><br/>

		<article class="getInsert">
		<input type=submit value="추가하기">
	</form>
</div>
</body>
</html>

<style>
	input[type=text], input[type=number] {
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

    .insertDirectorInfo {
		background-color: white;
		padding-top: 1px;
		padding-bottom: 10px;
		width: 600px;
		margin: 0 auto;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.getInsert {
		display: flex;
		justify-content: center;
	}
</style>