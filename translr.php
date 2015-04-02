<?php
	if ((isset($_POST["lang"]) && $_POST["lang"] != "") || (isset($_COOKIE["language"]) && $_COOKIE["language"] != "")) {
		$lang = (isset($_POST["lang"]) && $_POST["lang"] != "" ? $_POST["lang"] : $_COOKIE["language"]);
		include("connect.php");
		error_reporting(0);
		$res = retResult("lang_".strtolower($lang), "");
		if ($res) {
			$translr;
			while ($row = mysqli_fetch_array($res)) {
				$translr[$row["Key"]] = $row["Value"];
			}
			echo json_encode($translr);
		}
		else {
			echo "ERROR_404";
		}
		mysqli_close($con);
	}
	else {
		echo "ERROR_400";
	}
?>