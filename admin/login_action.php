<?php
	include ("connect.php");
	include ("functions.php");
	if (isset($_GET["redirectTo"])) { $_GET["redirectTo"] = str_replace("'", "", strip_tags($_GET["redirectTo"])); }
	if (checkLogin(false)) { header("Location: ".(isset($_GET["redirectTo"]) ? $_GET["redirectTo"] : "index.php")); exit; }
	else {
		if (isset($_POST["Username"]) && isset($_POST["Password"]) && $_POST["Username"] != "" && $_POST["Password"] != "") {
			$_POST["Username"] = str_replace("'", "", strip_tags($_POST["Username"]));
			$_POST["Password"] = str_replace("'", "", strip_tags($_POST["Password"]));
			$res = retResult("users", "WHERE Username='".$_POST["Username"]."'");
			if ($res) {
				$row = mysqli_fetch_array($res);
				$pass = hash("sha512", $_POST["Password"]."_".$row["PassWSalt"]);
				if ($row["Password"] == $pass) {
					$sessid = $_POST["Username"]."_".rand(1000000000, 9999999999);
					mysqli_query($con, "UPDATE users SET CurrSessID='".$sessid."', LastLogin='".time()."', LastIP='".$_SERVER['REMOTE_ADDR']."' WHERE ID='".$row["ID"]."'");
					setcookie("username", $_POST["Username"], time() + 60*60*24*14);
					setcookie("sess_id", $sessid, time() + 60*60*24*14);
					header("Location: ".(isset($_GET["redirectTo"]) ? $_GET["redirectTo"] : "index.php"));
					exit;
				}
				else {
					header("Location: login.php?error=password".(isset($_GET["redirectTo"]) ? "&redirectTo=".$_GET["redirectTo"] : ""));
					exit;
				}
			}
			else {
				header("Location: login.php?error=username".(isset($_GET["redirectTo"]) ? "&redirectTo=".$_GET["redirectTo"] : ""));
				exit;
			}
		}
		else {
			header("Location: login.php?error=form".(isset($_GET["redirectTo"]) ? "&redirectTo=".$_GET["redirectTo"] : ""));
			exit;
		}
	}
?>