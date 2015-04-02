<?php
	if (isset($_POST["action"]) && $_POST["action"] != "") {
		include("connect.php");
		switch ($_POST["action"]) {
			case "retrieve":
				$res = retResult("languages", "");
				if ($res) {
					$arrlg = array(); $arrkeys = array();
					$joins = ""; $prec = "";
					$row = mysqli_fetch_array($res);
					array_push($arrlg, $row["LangCode"]);
					$joins = ""; $select = "";
					$prec = $row["LangCode"];
					$init = $row["LangCode"];
					$keys = "";
					$keys = "`lang_".$row["LangCode"]."`.`Key`";
					while ($row = mysqli_fetch_array($res)) {
						array_push($arrlg, $row["LangCode"]);
						$keys .= ", `lang_".$row["LangCode"]."`.`Key`";
						$joins .= "LEFT JOIN `lang_".$row["LangCode"]."` ON `lang_".$row["LangCode"]."`.`Key`=`lang_".$prec."`.`Key` ";
						$prec = $row["LangCode"];
					}
					$joins = "SELECT COALESCE(".$keys.") AS `Key` FROM `lang_".$init."` ".$joins;
					$joins .= " UNION ".str_replace("LEFT", "RIGHT", $joins);
					$res = mysqli_query($con, $joins);
					if ($res) {
						while ($row = mysqli_fetch_array($res)) {
							array_push($arrkeys, $row["Key"]);
						}
					}
					$json = '{"langs": '.json_encode($arrlg).', "keys": '.json_encode($arrkeys).'}';
					echo $json;
				}
				break;
			case "create":
				if (isset($_POST["Key"]) && $_POST["Key"] != "") {
					$_POST["Key"] = str_replace("'", "", strip_tags(urldecode($_POST["Key"])));
					$res = retResult("languages", "");
					if ($res) {
						while ($row = mysqli_fetch_array($res)) {
							if (isset($_POST["value-".$row["LangCode"]])) {
								mysqli_query($con, "INSERT INTO `lang_".$row["LangCode"]."` (`Key`, `Value`) VALUES ('".$_POST["Key"]."', '".base64_encode(urldecode($_POST["value-".$row["LangCode"]]))."')");
							}
						}
					}
				}
				break;
		}
	}
?>