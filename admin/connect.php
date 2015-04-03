<?php
	date_default_timezone_set('Europe/Bucharest');
	$con = mysqli_connect("mysql.hostinger.ro", "u517020093_haufe", "haufe12345", "u517020093_haufe");
	if (mysqli_connect_errno($con)) {
		echo "Eroare la conectare : ".mysqli_connect_error();
	}
	else {
		mysqli_set_charset($con, "utf8");
	}
	function retResult($tbl, $arg) {
		global $con;
		$res = mysqli_query($con, "SELECT * FROM ".$tbl." ".$arg);
		if (!is_bool($res) && mysqli_num_rows($res)) {
			return $res;
		}
		else {
			return 0;
		}
	}
?>