<?php
	include "header.php";
?>

<?php

	include "connectDB.php";

?>

<h1 align="center"> 배우 추가하기 </h1>
<div class=insertActorInfo>
	<form action="insert_actor_sql.php" method=POST>
		<br/>
		이름
		<input type=text name="actorName">
		<br/><br/>
		성별
		<select name="actorSex">
			<option value="남성"> 남성 </option>
			<option value="여성" selected> 여성 </option>
		</select>
		<br/><br/>
		나이
		<input type=number name="actorAge">
		<br/><br/>
		키
		<input type=number name="actorHeight">
		<br/><br/>
		논란
		<select name="issue">
			<option value="1" selected> 논란 없음 </option>
			<option value="2"> 음주운전 </option>
			<option value="3"> 마약 </option>
			<option value="4"> 성범죄 </option>
			<option value="5"> 인성 논란 </option>
		</select>

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

    .insertActorInfo {
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