<?php
	include ("connect.php");
	include ("functions.php");
	checkLogin();
	if (isset($_GET["what"]) && isset($_GET["id"]) && $_GET["what"] != "" && $_GET["id"] != "") {
		if ($_GET["what"] != "definitions") {
			if ($_GET["what"] == "languages") {
				$res = retResult("languages", "WHERE ID='".$_GET["id"]."'");
				if ($res) {
					$row = mysqli_fetch_array($res);
					mysqli_query($con, "DROP TABLE `lang_".$row["LangCode"]."`");
				}
			}
			mysqli_query($con, "DELETE FROM `".$_GET["what"]."` WHERE ID='".$_GET["id"]."'");
		}
		else {
			$key = urldecode($_GET["id"]);
			$res = retResult("languages", "");
			while ($row = mysqli_fetch_array($res)) {
				mysqli_query($con, "DELETE FROM `lang_".$row["LangCode"]."` WHERE `Key`='".$key."'");
			}
		}
		mysqli_close($con);
		header("Location: view.php?what=".$_GET["what"]);
		exit;
	}
	else {
		echo 'Bad request. Please try again.';
	}
?>