<?php
	include ("connect.php");
	include ("functions.php");
	if (isset($_POST["create"]) && $_POST["create"] != "") {
		$vid = ""; $sessid = ""; $visid = "";
		if (isset($_COOKIE["visitor_id"]) && $_COOKIE["visitor_id"] != "") {
			$vid = $_COOKIE["visitor_id"];
			mysqli_query($con, "UPDATE `visitors` SET `LastVisit`='".time()."' WHERE vID='".$vid."'");
		}
		else {
			mysqli_query($con, "INSERT INTO `visitors`(`FirstVisit`, `LastVisit`, `Browser`, `OS`, `Mobile`) VALUES ('".time()."', '".time()."', '".browser_nv_ro()."', '".OS()."', '".checkMobile()."')");
			$vid = mysqli_insert_id($con);
		}
		if (isset($_COOKIE["session_id"]) && $_COOKIE["session_id"] != "") {
			$sessid = $_COOKIE["session_id"];
		}
		else {
			$sessid = $vid."_".rand(100000,999999);
			mysqli_query($con, "INSERT INTO `sessions`(`vID`, `sessID`, `Start`, `End`, `IP`) VALUES ('".$vid."', '".$sessid."', '".time()."', '0', '".$_SERVER['REMOTE_ADDR']."')");
		}
		if (isset($_POST["page"]) && $_POST["page"] != "") {
			if (isset($_COOKIE["visit_id"]) && $_COOKIE["visit_id"] != "") { mysqli_query($con, "UPDATE visits SET End='".time()."' WHERE visID='".$_COOKIE["visit_id"]."'"); }
			mysqli_query($con, "INSERT INTO `visits`(`vID`, `sessID`, `Page`, `Start`, `End`) VALUES ('".$vid."', '".$sessid."', '".$_POST["page"]."', '".time()."', '0')");
			$visid = mysqli_insert_id($con);
		}
		setcookie("visitor_id", $vid, time() + 60*60*24*356, "/");
		setcookie("session_id", $sessid, 0, "/");
		setcookie("visit_id", $visid, 0, "/");
		echo $visid;
	}
	else if (isset($_POST["close"]) && $_POST["close" ] != "") {
		if ((isset($_POST["visid"]) && $_POST["visid"] != "") || (isset($_COOKIE["visit_id"]) && $_COOKIE["visit_id"] != "")) {
			$visid = (isset($_POST["visid"]) ? $_POST["visid"] : $_COOKIE["visit_id"]);
			mysqli_query($con, "UPDATE visits SET End='".time()."' WHERE visID='".$visid."'");
			if (isset($_COOKIE["session_id"]) && $_COOKIE["session_id"] != "") { mysqli_query($con, "UPDATE sessions SET End='".time()."' WHERE sessID='".$_COOKIE["session_id"]."'"); }
			setcookie("visit_id", "");
		}
	}
	mysqli_close($con);
?>