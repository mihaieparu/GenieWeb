<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=no">
<title>View - Administration panel - HAUFE Lexware Romania</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" />
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/style.css" />
<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</head>
<body>
<?php
	include ("connect.php");
	include ("functions.php");
	checkLogin();
?>
<nav class="navbar navbar-default">
	<div class="container">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Menu</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><img src="images/haufe.svg" /></a>
        </div>
    
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="fa fa-fw fa-file-text"></span> Content <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="view.php?what=jobs">Jobs</a></li>
                    <li><a href="view.php?what=timeline">Timeline</a></li>
                    <li><a href="view.php?what=menu">Menu</a></li>
                    <li><a href="view.php?what=pages">Pages</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="fa fa-fw fa-globe"></span> Translr <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="view.php?what=languages">Languages</a></li>
                    <li><a href="view.php?what=definitions">Definitions</a></li>
                </ul>
            </li>
            <li><a href="view.php?what=routes"><span class="fa fa-fw fa-random"></span> Routes</a></li>
			<li><a href="view.php?what=users"><span class="fa fa-fw fa-users"></span> Users</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="fa fa-fw fa-pie-chart"></span> Statistics <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="view.php?what=statistics&type=summary">Summary</a></li>
                    <li><a href="edit.php?what=statistics&type=detailed">Detailed</a></li>
                </ul>
            </li>
            <li><a href="javascript:logout();"><span class="fa fa-fw fa-sign-out"></span> Log out</a></li>
          </ul>
        </div>
      </div>
	</div>
</nav>
<div class="container">
    <?php
		if (isset($_GET["what"]) && $_GET["what"] != "") {
			switch ($_GET["what"]) {
				case "jobs":
					echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Jobs';
					$res = retResult("jobs", "ORDER BY DateAdded DESC");
					if ($res) {
						echo ' <small>('.mysqli_num_rows($res).' elements)</small><a href="add.php?what=jobs" class="pull-right btn btn-default">Add new job</a></h1></div>';
						echo '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>#</td><th>Date added</th><th>Expire date</th><th>Ref.</th><th>Title</th><th>City</th><th>Online applications</th><th>Actions</th></tr></thead><tbody>';
						while ($row = mysqli_fetch_array($res)) {
							echo '<tr id="row-'.$row["ID"].'"><td>'.$row["ID"].'</td><td>'.date('d.m.Y', $row["DateAdded"]).'</td><td>'.($row["DateExpire"] < time() ? '<span class="badge" style="background-color: #ff0039 !important;" title="Expired on '.date('d.m.Y', $row["DateExpire"]).'" >Expired</span>' : date('d.m.Y', $row["DateExpire"])).'</td><td>#'.$row["Ref"].'</td><td>'.$row["Title"].'</td><td>'.$row["City"].'</td><td>'.($row["Applications"] == "1" ? "YES".($row["ApplicationEmail"] != "" ? ", ".$row["ApplicationEmail"] : "") : "").'</td><td><a href="edit.php?what=jobs&id='.$row["ID"].'" class="btn btn-sm btn-primary">Edit</a> <a href="delete.php?what=jobs&id='.$row["ID"].'" class="btn btn-sm btn-danger">Delete</a></td></tr>';
						}
						echo '</tbody></table></div>';
					}
					else {
						echo '<a href="add.php?what=jobs" class="pull-right btn btn-default">Add new job</a></div></h1>';
						echo '<center>No data to show.</center>';
					}
					break;
				case "menu":
					echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Menu';
					$res = retResult("menu", "ORDER BY `Order` ASC");
					if ($res) {
						echo ' <small>('.mysqli_num_rows($res).' elements)</small><a href="add.php?what=menu" class="pull-right btn btn-default">Add new menu element</a></h1></div>';
						echo '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>#</td><th>URL</th><th>Name</th><th>Title</th><th>Target</th><th>Actions</th><th>Order</th></tr></thead><tbody>';
						$i = 0;
						while ($row = mysqli_fetch_array($res)) {
							echo '<tr id="row-'.$row["ID"].'"><td>'.$row["ID"].'</td><td>'.$row["URL"].'</td><td>'.$row["Name"].'</td><td>'.$row["Title"].'</td><td>'.$row["Target"].'</td><td><a href="edit.php?what=menu&id='.$row["ID"].'" class="btn btn-sm btn-primary">Edit</a> <a href="delete.php?what=menu&id='.$row["ID"].'" class="btn btn-sm btn-danger">Delete</a></td><td class="row-arrows"><a href="javascript:moveUp('.$row["ID"].')"><span class="fa fa-fw fa-arrow-up"></span></a><a href="javascript:moveDown('.$row["ID"].')"><span class="fa fa-fw fa-arrow-down"></span></a></td></tr>';
							$i++;
						}
						echo '</tbody></table><a href="javascript:updateOrder();" class="pull-right btn btn-primary" style="display:none" id="update-order" data-hidden="true"><span class="fa fa-fw fa-refresh"></span> Update menu order</a></div>';
					}
					else {
						echo '<a href="add.php?what=menu" class="pull-right btn btn-default">Add new menu element</a></div></h1>';
						echo '<center>No data to show.</center>';
					}
					break;
				case "timeline":
					echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Timeline';
					$res = retResult("timeline", "ORDER BY Date DESC");
					if ($res) {
						echo ' <small>('.mysqli_num_rows($res).' elements)</small><a href="add.php?what=timeline" class="pull-right btn btn-default">Add new timeline element</a></h1></div>';
						echo '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>#</td><th>Date</th><th>Title</th><th>Actions</th></thead><tbody>';
						while ($row = mysqli_fetch_array($res)) {
							echo '<tr id="row-'.$row["ID"].'"><td>'.$row["ID"].'</td><td>'.date('d.m.Y', $row["Date"]).'</td><td>'.$row["Title"].'</td><td><a href="edit.php?what=timeline&id='.$row["ID"].'" class="btn btn-sm btn-primary">Edit</a> <a href="delete.php?what=timeline&id='.$row["ID"].'" class="btn btn-sm btn-danger">Delete</a></td></tr>';
						}
						echo '</tbody></table></div>';
					}
					else {
						echo '<a href="add.php?what=timeline" class="pull-right btn btn-default">Add new timeline element</a></div></h1>';
						echo '<center>No data to show.</center>';
					}
					break;
				case "pages":
					echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Pages';
					$res = retResult("pages", "ORDER BY DateAdded DESC");
					if ($res) {
						echo ' <small>('.mysqli_num_rows($res).' elements)</small><a href="add.php?what=pages" class="pull-right btn btn-default">Add new page</a></h1></div>';
						echo '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>#</td><th>Date added</th><th>Route</th><th>Title</th><th>Actions</th></tr></thead><tbody>';
						while ($row = mysqli_fetch_array($res)) {
							echo '<tr id="row-'.$row["ID"].'"><td>'.$row["ID"].'</td><td>'.date('d.m.Y', $row["DateAdded"]).'</td><td>'.$row["Route"].'</td><td>'.$row["Title"].'</td><td><a href="edit.php?what=pages&id='.$row["ID"].'" class="btn btn-sm btn-primary">Edit</a> <a href="delete.php?what=pages&id='.$row["ID"].'" class="btn btn-sm btn-danger">Delete</a></td></tr>';
						}
						echo '</tbody></table></div>';
					}
					else {
						echo '<a href="add.php?what=pages" class="pull-right btn btn-default">Add new page</a></div></h1>';
						echo '<center>No data to show.</center>';
					}
					break;
				case "languages":
					echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Languages';
					$res = retResult("languages", "ORDER BY `Default` DESC");
					if ($res) {
						echo ' <small>('.mysqli_num_rows($res).' elements)</small><a href="add.php?what=languages" class="pull-right btn btn-default">Add new language</a></h1></div>';
						echo '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>#</td><th>Flag (auto)</th><th>Language code</th><th>Language name</th><th>Language show-as</th><th>Actions</th></thead><tbody>';
						while ($row = mysqli_fetch_array($res)) {
							echo '<tr id="row-'.$row["ID"].'"><td>'.$row["ID"].'</td><td><img src="http://l10n.xwiki.org/xwiki/bin/download/L10N/Flags/'.$row["LangCode"].'.png"></img></td><td>'.$row["LangCode"].'</td><td>'.$row["LangName"].'</td><td>'.$row["LangShow"].'</td><td><a href="edit.php?what=languages&id='.$row["ID"].'" class="btn btn-sm btn-primary">Edit</a> <a href="delete.php?what=languages&id='.$row["ID"].'" class="btn btn-sm btn-danger">Delete</a></td></tr>';
						}
						echo '</tbody></table></div>';
					}
					else {
						echo '<a href="add.php?what=languages" class="pull-right btn btn-default">Add new language</a></div></h1>';
						echo '<center>No data to show.</center>';
					}
					break;
				case "definitions":
					$res = retResult("languages", "");
					if ($res) {
						$joins = ""; $prec = "";
						$row = mysqli_fetch_array($res);
						$joins = ""; $select = "";
						$prec = $row["LangCode"];
						$init = $row["LangCode"];
						$keys = "";
						$select = ", `lang_".$row["LangCode"]."`.`Value` AS `Value_".$row["LangCode"]."`";
						$keys = "`lang_".$row["LangCode"]."`.`Key`";
						while ($row = mysqli_fetch_array($res)) {
							$keys .= ", `lang_".$row["LangCode"]."`.`Key`";
							$select .= ", `lang_".$row["LangCode"]."`.`Value` AS `Value_".$row["LangCode"]."`";
							$joins .= "LEFT JOIN `lang_".$row["LangCode"]."` ON `lang_".$row["LangCode"]."`.`Key`=`lang_".$prec."`.`Key` ";
							$prec = $row["LangCode"];
						}
						$joins = "SELECT COALESCE(".$keys.") AS `Key`".$select." FROM `lang_".$init."` ".$joins;
						$joins .= " UNION ".str_replace("LEFT", "RIGHT", $joins);
						$res = mysqli_query($con, $joins);
						echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Definitions';
						if ($res) {
							$res1 = retResult("languages", "");
							$langs = array();
							echo ' <small>('.mysqli_num_rows($res).' elements)</small><a href="edit.php?what=definitions&id=all" class="pull-right btn btn-default">Edit all definitions</a> <a href="add.php?what=definitions" class="pull-right btn btn-default">Add new definition</a></h1></div>';
							echo '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>Key</th>';
							while ($row1 = mysqli_fetch_array($res1)) {
								array_push($langs, $row1["LangCode"]);
								echo '<th><img src="http://l10n.xwiki.org/xwiki/bin/download/L10N/Flags/'.$row1["LangCode"].'.png" /> '.$row1["LangName"].'</th>';
							}
							echo '<th>Actions</th></thead><tbody>';
							while ($row = mysqli_fetch_array($res)) {
								echo '<tr><td>'.$row["Key"].'</td>';
								foreach ($langs as $lng) {
									echo '<td>'.base64_decode($row["Value_".$lng]).'</td>';
								}
								echo '<td><a href="delete.php?what=definitions&id='.urlencode($row["Key"]).'" class="btn btn-sm btn-danger">Delete</a></td></tr>';
							}
							echo '</tbody></table></div>';
						}
						else {
							echo '<a href="add.php?what=definitions" class="pull-right btn btn-default">Add new definition</a></div></h1>';
							echo '<center>No data to show.</center>';
						}
					}
					break;
				case "routes":
					echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Routes';
					$res = retResult("routes", "ORDER BY Views DESC");
					if ($res) {
						echo ' <small>('.mysqli_num_rows($res).' elements)</small><a href="add.php?what=routes" class="pull-right btn btn-default">Add new route</a></h1></div>';
						echo '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>#</td><th>Route</th><th>Attributes received</th><th>Go to</th><th>Attributes sent</th><th>Views</th><th>Actions</th></thead><tbody>';
						while ($row = mysqli_fetch_array($res)) {
							echo '<tr id="row-'.$row["ID"].'"><td>'.$row["ID"].'</td><td>'.$row["Route"].'</td><td>'.$row["Attributes"].'</td><td>'.$row["RouteTo"].'</td><td>'.$row["RouteToAttributes"].'</td><td>'.$row["Views"].'</td><td><a href="edit.php?what=routes&id='.$row["ID"].'" class="btn btn-sm btn-primary">Edit</a> <a href="delete.php?what=routes&id='.$row["ID"].'" class="btn btn-sm btn-danger">Delete</a></td></tr>';
						}
						echo '</tbody></table></div>';
					}
					else {
						echo '<a href="add.php?what=routes" class="pull-right btn btn-default">Add new route</a></div></h1>';
						echo '<center>No data to show.</center>';
					}
					break;
				case "users":
					echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Users';
					$res = retResult("users", "WHERE Username != '".getUsername()."'");
					if ($res) {
						echo ' <small>('.mysqli_num_rows($res).' elements)</small><a href="add.php?what=users" class="pull-right btn btn-default">Add new user</a></h1></div>';
						echo '<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>#</td><th>Username</th><th>Last login</th><th>Last IP</th><th>Logged in</th><th>Actions</th></thead><tbody>';
						while ($row = mysqli_fetch_array($res)) {
							echo '<tr id="row-'.$row["ID"].'"><td>'.$row["ID"].'</td><td>'.$row["Username"].'</td><td>'.$row["LastLogin"].'</td><td>'.$row["LastIP"].'</td><td>'.($row["CurrSessID"] ? '<span class="fa fa-fw fa-check"></span>' : '<span class="fa fa-fw fa-times"></span>').'</td><td><a href="delete.php?what=users&id='.$row["ID"].'" class="btn btn-sm btn-danger">Delete</a></td></tr>';
						}
						echo '</tbody></table></div>';
					}
					else {
						echo '<a href="add.php?what=users" class="pull-right btn btn-default">Add new user</a></h1></div>';
						echo '<center>No data to show.</center>';
					}
					break;
				case "statistics":
					$type = "summary";
					if (isset($_GET["type"]) && $_GET["type"] != "") { $type = $_GET["type"]; };
					switch ($type) {
						case "summary":
							
							break;
						case "detailed":
							
							break;
					}
					break;
			}
		}
		else {
			echo 'Bad request. Please try again.';
		}
	?>
</div>
</body>
</html>