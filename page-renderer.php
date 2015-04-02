<?php
	if (isset($_POST["route"]) && isset($_POST["attr"]) && $_POST["route"] != "" && $_POST["attr"] != "") {
		include("connect.php");
		$_POST["route"] = str_replace("'", "", strip_tags($_POST["route"]));
		$res = retResult("routes", "WHERE `Route`='".$_POST["route"]."'");
		if ($res) {
			$row = mysqli_fetch_array($res);
			$res = retResult("pages", "WHERE `Route`='".$row["RouteTo"]."'");
			if ($res) {
				$row = mysqli_fetch_array($res);
				$attr = json_decode($_POST["attr"], true);
				$torender = json_decode($row["Content"], true);
				$rendered = array();
				$rendered["Title"] = $row["Title"];
				if (array_key_exists("jumbotron", $torender)) { $rendered["PageContent"]["jumbotron"] = $torender["jumbotron"]; }
				if (array_key_exists("scrollspy", $torender)) { $rendered["PageContent"]["scrollspy"] = $torender["scrollspy"]; }
				for ($i = 0; $i < count($torender["content"]); $i++) {
					$curr = array();
					if (array_key_exists("switch", $torender["content"][$i])) {
						$switchby = $torender["content"][$i]["switch"]["by"];
						if (array_key_exists($switchby, $attr)) {
							if (array_key_exists($attr[$switchby], $torender["content"][$i]["switch"]["cases"])) {
								$curr = $torender["content"][$i]["switch"]["cases"][$attr[$switchby]];
							}
						}
					}
					else {
						$curr = $torender["content"][$i];
					}
					if ($curr) { $rendered["PageContent"]["content"][$i] = $curr; }
				}
				echo json_encode($rendered);
			}
			else {
				echo "ERROR_404";
			}
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