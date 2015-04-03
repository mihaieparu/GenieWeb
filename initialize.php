<?php
	function set_language() {
		if (isset($_COOKIE["language"]) && $_COOKIE["language"] != "") {
			$lang = $_COOKIE["language"];
		}
		else {
			$res = retResult("languages", "WHERE `Default`='1'");
			if ($res) {
				$row = mysqli_fetch_array($res);
				setcookie("language", $row["LangCode"], 0, "/");
				$lang = $row["LangCode"];
			}
			else {
				setcookie("language", "en", 0, "/");
				$lang = "en";
			}
		}
		return $lang;
	}
	
	$lang = set_language();
	
	//check browsers
?>