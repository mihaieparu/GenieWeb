<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=no">
<title>Administration panel - HAUFE Lexware Romania</title>
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
          <a class="navbar-brand" href="#"><img src="images/haufe.svg" /></a>
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
    <div class="jumbotron">
      <h1>Welcome!</h1>
      <p>Welcome to your administration panel! Here you can update all your website's content because it uses JSON rendering and Translr, the stunning feature which translates live your content! For more documentation, you can click the button below.</p>
      <p><a href="readme.docx" class="btn btn-primary btn-lg">Documentation</a></p>
    </div>
    <div class="row">
    	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        	<div class="panel panel-default">
            	<div class="panel-heading"><center>Pages</center></div>
                <div class="panel-body">
                	There are currently <b>
                	<?php
						$res = retResult("pages", "");
						if ($res) {
							echo mysqli_num_rows($res);
						}
						else {
							echo 0;
						}
					?>
                    </b> pages in the database.<br /><br /><center><a class="btn btn-primary btn-sm" href="view.php?what=pages"><span class="fa fa-fw fa-arrow-right"></span> Go to pages</a></center>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        	<div class="panel panel-default">
            	<div class="panel-heading"><center>Translr</center></div>
                <div class="panel-body">
                	There are currently <b>
                	<?php
						$res = retResult("languages", "");
						if ($res) {
							echo mysqli_num_rows($res);
						}
						else {
							echo 0;
						}
					?>
                    </b> languages in the database, which describe <b>
                    <?php
						if ($res) { 
							$joins = ""; $prec = "";
							$row = mysqli_fetch_array($res);
							$joins = "SELECT * FROM `lang_".$row["LangCode"]."` ";
							$prec = $row["LangCode"];
							while ($row = mysqli_fetch_array($res)) {
								$joins .= "LEFT JOIN `lang_".$row["LangCode"]."` ON `lang_".$row["LangCode"]."`.`Key`=`lang_".$prec."`.`Key` ";
								$prec = $row["LangCode"];
							}
							$joins = $joins." UNION ".str_replace("LEFT", "RIGHT", $joins);
							$res = mysqli_query($con, $joins);
							if ($res) {
								echo mysqli_num_rows($res);
							}
						}
					?>
                    </b> Translr definitions.<br /><br /><center><a class="btn btn-primary btn-sm" href="view.php?what=definitions"><span class="fa fa-fw fa-arrow-right"></span> Go to Translr definitions</a></center>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        	<div class="panel panel-default">
            	<div class="panel-heading"><center>Users</center></div>
                <div class="panel-body">
                	There are currently <b>
                	<?php
						$res = retResult("users", "");
						if ($res) {
							echo mysqli_num_rows($res);
						}
						else {
							echo 0;
						}
					?>
                    </b> users in the database.<br /><br /><center><a class="btn btn-primary btn-sm" href="view.php?what=users"><span class="fa fa-fw fa-arrow-right"></span> Go to users</a></center>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>