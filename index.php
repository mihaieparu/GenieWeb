<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="title" content="HAUFE Lexware Romania" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Official website for HAUFE Lexware Romania" />
<meta name="keywords" content="haufe, lexware, romania, timisoara, haufe lexware, timis" />
<meta name="robots" content="index, follow"/>
<meta name="viewport" content="width=device-width, user-scalable=no">
<title>HAUFE Lexware Romania</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" />
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/css/bootstrap.min.css" />
<link rel="stylesheet" href="/css/style.css" />
<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script><?php echo 'var ign_pathname = "'.(strpos($_SERVER['REQUEST_URI'], ".") !== FALSE ? dirname($_SERVER['REQUEST_URI']) : '/').'";'; ?></script>
<script src="/js/stats.js"></script>
<script src="/js/script.js"></script>
<script src="/js/mobile.js"></script>
<script src="/js/animations.js"></script>
<script src="/js/base64.js"></script>
<script src="/js/translr.js"></script>
<script src="/js/scrollspy.js"></script>
<script src="/js/ajax.js"></script>
</head>
<?php
	include ("connect.php");
	include ("initialize.php");
?>
<body onunload="javascript:closeVisit();">
<?php
	$file = file_get_contents("loadimg.config");
	$imgs = explode("\n", $file);
	if ($imgs) {
		echo '<imglist count="'.count($imgs).'">';
		for ($i = 0; $i < count($imgs); $i++) {
			echo '<element id="img-'.$i.'" src="'.$imgs[$i].'"></element>';
		}
		echo '</imglist>';
	}
	$res = retResult("languages", "");
	if ($res && mysqli_num_rows($res) > 1) {
		echo '<langlist>';
		while ($row = mysqli_fetch_array($res)) {
			echo '<lang id="lang-'.$row["ID"].'" data-langcode="'.$row["LangCode"].'" data-langname="'.$row["LangName"].'" data-langshow="'.$row["LangShow"].'"'.($lang == $row["LangCode"] ? ' data-active="true"' : '').'></lang>';
		}
		echo '</langlist>'."\n";
	}
	$res = retResult("menu", "ORDER BY `Order` ASC");
	if ($res) {
		echo '<menu>';
		while ($row = mysqli_fetch_array($res)) {
			echo '<element id="el-'.$row["ID"].'" '.($row["Target"] == "_self" ? 'data-useajax="true" ' : '').'data-URL="'.$row["URL"].'" data-target="'.$row["Target"].'" ';
			if (substr($row["Title"], 0, 1) == "@") {
				echo 'data-usetranslr-title="true" ';
			}
			echo 'data-title="'.$row["Title"].'" ';
			if (substr($row["Name"], 0, 1) == "@") {
				echo 'data-usetranslr-value="true" ';
			}
			echo 'data-value="'.$row["Name"].'"></element>';
		}
		echo '</menu>'."\n";
	}
?>
<div id="header">
	<a data-ajax-go="home" href="#" id="logo" alt="Haufe" title="HAUFE Lexware Romania" data-center-y="true"></a>
    <div id="menu" data-center-y="true"></div>
</div>
<div id="loader" class="full-size">
	<div class="blackfix">&nbsp;</div>
	<div id="loader-div" data-center-x="true" data-center-y="true" class="autoh">
		<div class='uil-spin-css'><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>
    </div>
</div>
<div id="content">
</div>
<script>
	var curr_route = ""; var curr_attr = "", curr_routeto = "";
	<?php
		if (isset($_GET["route"]) && $_GET["route"] != "") {
			if (isset($_GET["attributes"]) && $_GET["attributes"] != "") {
				echo 'routeTo("'.$_GET["route"].'", "'.$_GET["attributes"].'");'."\n";
			}
			else {
				echo 'routeTo("'.$_GET["route"].'");'."\n";
			}
		}
		else {
			echo 'routeTo("home");'."\n";
		}
	?>
	animateAll();
</script>
</body>
</html>