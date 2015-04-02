<?php
	if (isset($_POST["route"]) && isset($_POST["type"]) && $_POST["route"] != "" && $_POST["type"] != "") {
		include("connect.php");
		$_POST["route"] = str_replace("'", "", strip_tags($_POST["route"]));
		$res = retResult("routes", "WHERE `Route`='".$_POST["route"]."'");
		if ($res) {
			$row = mysqli_fetch_array($res);
			switch ($_POST["type"]) {
				case "route":
					echo $row["RouteTo"];
					mysqli_query($con, "UPDATE routes SET Views=Views+1 WHERE `Route`='".$_POST["route"]."'");
					break;
				case "attr":
					$attr = (object) array('attributes' => $row["Attributes"], 'routetoattributes' => $row["RouteToAttributes"]);
					echo json_encode($attr);
					break;
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