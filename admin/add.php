<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=no">
<title>Add - Administration panel - HAUFE Lexware Romania</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" />
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/style.css" />
<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
<script src="js/tinymce/tinymce.min.js"></script>
<script>
	var tm_fonts ="Titillium Web=Titillium Web;PT Serif=PT Serif";
	tinymce.init({
		selector: ".tinymce-ta",
		language: "en_GB",
		content_css: "css/style.css",
		font_formats: tm_fonts,
		width: "100%",
		height: "175px",
		plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen template paste textcolor",
			"insertdatetime media nonbreaking save table contextmenu directionality",
		],
		toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		toolbar2: "fontselect fontsizeselect | print preview media | forecolor backcolor emoticons",
		image_advtab: true,
	 });
</script>
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
	<div class="modal fade">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Select Translr key</h4>
          </div>
          <div class="modal-body">
            <form action="javascript:addKey('select-key-form');" id="select-key-form"><select id="translr-keys" onchange="$('#select-key-form').submit();" class="form-control"></select></form>
            <hr />
            <form action="javascript:addKey('add-key-form');" id="add-key-form"></form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <?php
		if (isset($_POST["is_form"]) && $_POST["is_form"] == "true") {
			if (isset($_GET["what"]) && $_GET["what"] != "") {
				if ($_GET["what"] != "definitions") {
					$querydata = "INSERT INTO `".$_GET["what"]."`";
					switch($_GET["what"]) {
						case "jobs":
							$dexp = explode("-", $_POST["DateExpire"]);
							$querydata .= "(`DateAdded`, `DateExpire`, `Ref`, `Title`, `City`, `Description`, `Applications`, `ApplicationEmail`) VALUES ('".time()."', '".mktime(23,59,0,$dexp[1],$dexp[2],$dexp[0])."', '".$_POST["Ref"]."', '".$_POST["Title"]."', '".$_POST["City"]."', '".base64_encode($_POST["Description"])."', '".(isset($_POST["Applications"]) ? '1' : '0')."', '".$_POST["ApplicationEmail"]."')";
							break;
						case "menu":
							$querydata .= "(`URL`, `Name`, `Title`, `Target`, `Order`) VALUES ('".$_POST["URL"]."', '".$_POST["Name"]."', '".$_POST["Target"]."', '".$_POST["Order"]."')";
							break;
						case "timeline":
							$dexp = explode("-", $_POST["Date"]);
							$querydata .= "(`Date`, `Title`, `Content`) VALUES ('".mktime(23,59,0,$dexp[1],$dexp[2],$dexp[0])."', '".$_POST["Title"]."', '".base64_encode($_POST["Content"])."')";
							break;
						case "pages":
							$querydata .= "(`Route`, `DateAdded`, `Title`, `Content`) VALUES ('".$_POST["Route"]."', '".time()."', '".$_POST["Title"]."', '".$_POST["Content"]."')";
							break;
						case "languages":
							$querydata .= "(`LangCode`, `LangName`, `LangShow`, `Default`) VALUES ('".$_POST["LangCode"]."', '".$_POST["LangName"]."', '".$_POST["LangShow"]."', '".$_POST["Default"]."')";
							break;
						case "routes":
							$querydata .= "(`Route`, `Attributes`, `RouteTo`, `RouteToAttributes`, `Views`) VALUES ('".$_POST["Route"]."', '".$_POST["Attributes"]."', '".$_POST["RouteTo"]."', '".$_POST["RouteToAttributes"]."', '0')";
							break;
						case "users":
							$str = "abcdefghijklmnopqrstuvxwyzABCDEFGHIJKLMNOPQRSTUVXWYZ0123456789";
							$pass_hash = "";
							for ($i = 0; $i < 512; $i++) {
								$pass_hash .= $str[rand(0, strlen($str) - 1)];
							}
							$pass = hash("sha512", $_POST["Password"]."_".$pass_hash);
							$querydata .= "(`Username`, `Password`, `PassWSalt`) VALUES ('".$_POST["Username"]."', '".$pass."', '".$pass_hash."')";
							break;
					}
					mysqli_query($con, $querydata);
					echo '<script>location.href="view.php?what='.$_GET["what"].'";</script>';
				}
				else {
					
				}
			}
		}
		else {
			if (isset($_GET["what"]) && $_GET["what"] != "") {
				switch ($_GET["what"]) {
					case "jobs":
						echo '<script>function checkToggle() { if (!$("#online-apps").prop("checked")) { $("#app-email").attr("disabled", "disabled"); } else { $("#app-email").removeAttr("disabled"); } }</script><div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Add a new job</h1></div>';
						echo '<form action="add.php?what=jobs" method="POST"><input type="hidden" name="is_form" value="true" /><div class="row"><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2" style="margin-bottom:25px"><div class="input-group"><span class="input-group-addon">Title</span><input type="text" class="form-control" name="Title" required data-translr-await-id="1" placeholder="Title" /><span class="input-group-btn"><button class="btn btn-primary" onclick="javascript:triggerTranslrModal(1);" type="button" style="height:43px" title="Select Translr key"><span class="fa fa-fw fa-globe"></span></button></span></div></br><div class="input-group"><span class="input-group-addon">Expire date</span><input type="date" required name="DateExpire" class="form-control" placeholder="Expire date" /></div></br><div class="input-group"><span class="input-group-addon">Ref. #</span><input type="text" name="Ref" class="form-control" placeholder="Reference #" /></div></br><div class="input-group"><span class="input-group-addon">City</span><input type="text" name="City" class="form-control" placeholder="City" /></div></br><div class="input-group"><span class="input-group-addon"><input type="checkbox" name="Applications" id="online-apps" checked="checked" onchange="javascript:checkToggle();" /></span><input type="email" name="ApplicationEmail" class="form-control" id="app-email" placeholder="Applications email" /></div><br /><textarea class="tinymce-ta" name="Description" required></textarea><br /><center>You can use Translr keys in your content! Use the <kbd>@translr-<i>key</i></kbd> instead of the regular <kbd>@<i>key</i></kbd>.</center><br /><center><button type="submit" class="btn btn-default" style="margin-bottom:25px"><span class="fa fa-fw fa-plus"></span> Add job!</button></center></div></form>';
						break;
					case "menu":
						echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Add a new menu element</h1></div>';
						echo '<form action="add.php?what=menu" method="POST"><input type="hidden" name="is_form" value="true" /><div class="row"><div style="margin-bottom:25px" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-3 col-md-offset-3"><div class="input-group"><span class="input-group-addon">URL</span><input type="text" class="form-control" name="URL" required placeholder="URL" /></div></br><div class="input-group"><span class="input-group-addon">Name</span><input type="text" required name="Name" class="form-control" placeholder="Name" data-translr-await-id="1" /><span class="input-group-btn"><button class="btn btn-primary" onclick="javascript:triggerTranslrModal(1);" type="button" style="height:43px" title="Select Translr key"><span class="fa fa-fw fa-globe"></span></button></span></div></br><div class="input-group"><span class="input-group-addon">Title</span><input type="text" name="Title" class="form-control" placeholder="Title" data-translr-await-id="2" /><span class="input-group-btn"><button class="btn btn-primary" onclick="javascript:triggerTranslrModal(2);" type="button" style="height:43px" title="Select Translr key"><span class="fa fa-fw fa-globe"></span></button></span></div></br><div class="input-group"><span class="input-group-addon">Target</span><select name="Target" required class="form-control" /><option value="_self">in the same window/tab</option><option value="_blank">in another window/tab</option></select></div></div><br /></div><center><button type="submit" class="btn btn-default" style="margin-bottom:25px"><span class="fa fa-fw fa-plus"></span> Add menu element!</button></center></div></form>';
						break;
					case "timeline":
						echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Add a new timeline element</h1></div>';
						echo '<form action="add.php?what=timeline" method="POST"><input type="hidden" name="is_form" value="true" /><div class="row"><div style="margin-bottom:25px" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-3 col-md-offset-3"><div class="input-group"><span class="input-group-addon">Date</span><input type="date" class="form-control" name="Date" required placeholder="Date" /></div></br><div class="input-group"><span class="input-group-addon">Title</span><input type="text" required name="Title" class="form-control" placeholder="Title" data-translr-await-id="1" /><span class="input-group-btn"><button class="btn btn-primary" onclick="javascript:triggerTranslrModal(1);" type="button" style="height:43px" title="Select Translr key"><span class="fa fa-fw fa-globe"></span></button></span></div></br><div class="input-group"><span class="input-group-addon">Content</span><textarea name="Content" style="resize:vertical;height:150px" class="form-control" placeholder="Content" data-translr-await-id="2" /></textarea><span class="input-group-btn"><button class="btn btn-primary" onclick="javascript:triggerTranslrModal(2,1);" type="button" style="height:43px" title="Select Translr key"><span class="fa fa-fw fa-globe"></span></button></span></div></div></div><center><button type="submit" class="btn btn-default" style="margin-bottom:25px"><span class="fa fa-fw fa-plus"></span> Add timeline element!</button></center></div></form>';
						break;
					case "pages":
						echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Add a new page</h1></div>';
						echo '<form action="add.php?what=pages" method="POST"><input type="hidden" name="is_form" value="true" /><div class="row"><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2" style="margin-bottom:25px"><div class="input-group"><span class="input-group-addon">Title</span><input type="text" class="form-control" name="Title" required data-translr-await-id="1" placeholder="Title" /><span class="input-group-btn"><button class="btn btn-primary" onclick="javascript:triggerTranslrModal(1);" type="button" style="height:43px" title="Select Translr key"><span class="fa fa-fw fa-globe"></span></button></span></div></br><div class="input-group"><span class="input-group-addon">Route</span><input type="text" required name="Route" class="form-control" placeholder="Route" /></div>';
						echo '<br/><div class="input-group"><span class="input-group-addon">Content</span><textarea name="Content" style="resize:vertical;height:150px" class="form-control" placeholder="Content" data-translr-await-id="2" />{"jumbotron":{"id":"","type":"","size":"","src":"","h1":"","divider":"true","h2":"","buttons":{"0": {"type":"firststop"}}},"scrollspy":"","content":{}}</textarea><span class="input-group-btn"><button class="btn btn-primary" onclick="javascript:triggerTranslrModal(2,1);" type="button" style="height:43px" title="Select Translr key"><span class="fa fa-fw fa-globe"></span></button></span></div>';
						echo '<br /><center><button type="submit" class="btn btn-default" style="margin-bottom:25px"><span class="fa fa-fw fa-plus"></span> Add page!</button></center></div></form>';
						break;
					case "languages":
						echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Add a new language</h1></div>';
						echo '<form action="add.php?what=language" method="POST"><input type="hidden" name="is_form" value="true" /><div class="row"><div style="margin-bottom:25px" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-3 col-md-offset-3"><div class="input-group"><span class="input-group-addon">Language code</span><input type="text" class="form-control" name="LangCode" required placeholder="ISO 639-1 language code" /></div></br><div class="input-group"><span class="input-group-addon">Language name</span><input type="text" required name="LangName" class="form-control" placeholder="Language full name" /></div></br><div class="input-group"><span class="input-group-addon">Language show-as</span><input type="text" name="LangShow" class="form-control" placeholder="Language show-as" required /></div><br /><center><input type="checkbox" name="Default" id="def" /> <label for="def">Default language</label></div></div><center><button type="submit" class="btn btn-default" style="margin-bottom:25px"><span class="fa fa-fw fa-plus"></span> Add language!</button></center></div></form>';
						break;
					case "definitions":
						echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Add a new definition</h1></div>';
						echo '<form action="add.php?what=definitions" method="POST"><input type="hidden" name="is_form" value="true" /><div class="row"><div style="margin-bottom:25px" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-3 col-md-offset-3"><div class="input-group"><span class="input-group-addon">Key</span><input type="text" class="form-control" name="Key" required placeholder="Key (e.g. @key/example)" /></div><br />';
						$res = retResult("languages", "");
						if ($res) {
							while ($row = mysqli_fetch_array($res)) {
								echo '<div class="input-group"><span class="input-group-addon"><img src="http://l10n.xwiki.org/xwiki/bin/download/L10N/Flags/'.$row["LangCode"].'.png" /></span><input type="text" name="value-'.$row["LangCode"].'" class="form-control" placeholder="String for '.$row["LangName"].'" /></div><br/>';
							}
						}
						echo '</div></div><center><button type="submit" class="btn btn-default" style="margin-bottom:25px"><span class="fa fa-fw fa-plus"></span> Add definition!</button></center></form>';
						break;
					case "routes":
						echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Add a new route</h1></div>';
						echo '<form action="add.php?what=routes" method="POST"><input type="hidden" name="is_form" value="true" /><div class="row"><div style="margin-bottom:25px" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-3 col-md-offset-3"><div class="input-group"><span class="input-group-addon">Route</span><input type="text" class="form-control" name="Route" required placeholder="Route" /></div></br><div class="input-group"><span class="input-group-addon">Attributes</span><input type="text" required name="Attributes" class="form-control" placeholder="Attributes received (e.g. %attr1%/%attr2%)" /></div></br><div class="input-group"><span class="input-group-addon">Route to</span><input type="text" name="RouteTo" class="form-control" required placeholder="Route to" /></div></br><div class="input-group"><span class="input-group-addon">Attributes sent</span><input type="text" name="RouteToAttributes" class="form-control" placeholder="Attributes sent (e.g. attr1=%attr1%/attr2=%attr2%)" /></div></div></div><center><button type="submit" class="btn btn-default" style="margin-bottom:25px"><span class="fa fa-fw fa-plus"></span> Add route!</button></center></div></form>';
						break;
					case "users":
						echo '<div class="page-header" style="margin-top:0"><h1 style="margin-top:10px">Add a new user</h1></div>';
						echo '<form action="add.php?what=users" method="POST"><input type="hidden" name="is_form" value="true" /><div class="row"><div style="margin-bottom:25px" class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-3 col-md-offset-3"><div class="input-group"><span class="input-group-addon">Username</span><input type="text" class="form-control" name="Username" required placeholder="Username" /></div></br><div class="input-group"><span class="input-group-addon">Password</span><input type="password" required name="Password" class="form-control" required placeholder="Password" /></div></div></div><center><button type="submit" class="btn btn-default" style="margin-bottom:25px"><span class="fa fa-fw fa-plus"></span> Add user!</button></center></div></form>';
						break;
					default:
						echo 'Bad request. Please try again.';
						break;
				}
			}
			else {
				echo 'Bad request. Please try again.';
			}
		}
	?>
</div>
</body>
</html>