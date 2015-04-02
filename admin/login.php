<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=no">
<title>Log in - Administration panel - HAUFE Lexware Romania</title>
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
	if (isset($_GET["redirectTo"])) { $_GET["redirectTo"] = str_replace("'", "", strip_tags($_GET["redirectTo"])); }
	if (checkLogin(false)) { header("Location: ".(isset($_GET["redirectTo"]) ? $_GET["redirectTo"] : "index.php")); exit; }
?>
<div class="panel panel-primary" id="login" data-center-x="true" data-center-y="true">
	<div class="panel-heading">
		<h3 class="panel-title"><center><img src="images/haufe.svg" style="margin:5px 0;" /></center></h3>
	</div>
	<div class="panel-body">
    	<?php
			if (isset($_GET["logout"]) && $_GET["logout"] == "true") {
				echo '<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">×</button>You`ve been logged off successfully!</div><br />';
			}
			else if (isset($_GET["error"]) && $_GET["error"] != "") {
				switch ($_GET["error"]) {
					case "username":
						echo '<div class="alert alert-danger">Please check your username and then try again!</div>';
						break;
					case "password":
						echo '<div class="alert alert-danger">Please check your password and then try again!</div>';
						break;
					default:
						echo '<div class="alert alert-danger">An error occured. Please check your form and try again.</div>';
						break;
				}
				echo '<br />';
			}
		?>
		<form action="login_action.php<?php if (isset($_GET["redirectTo"])) { echo "?redirectTo=".$_GET["redirectTo"]; }?>" method="POST">
        	<input type="text" class="form-control" placeholder="Username" name="Username" id="username" required /><br />
            <input type="password" class="form-control" placeholder="Password" name="Password" id="password" required /><br /><br />
            <button type="submit" style="width:100%" class="btn btn-primary"><span class="fa fa-fw fa-sign-in"></span> Log in</button>
        </form>
	</div>
</div>
</body>
</html>