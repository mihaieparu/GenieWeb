<?php
	if (isset($_GET["action"]) && $_GET["action"] == "send") {
		if (isset($_POST["email"]) && $_POST["email"] != "") {
			include("connect.php");
			$_POST["email"] = str_replace("'", "", strip_tags($_POST["email"]));
			$res = retResult("newsletter_emails", "WHERE Email='".$_POST["email"]."'");
			if (!$res) {
				mysqli_query($con, "INSERT INTO `newsletter_emails`(`Email`) VALUES ('".$_POST["email"]."')");
			}
			mysqli_close($con);
		}
	}
	else if (isset($_POST["scrollspy"]) && isset($_POST["id"]) && $_POST["scrollspy"] && $_POST["id"] != "") {
		if (!(isset($_COOKIE["newsletter"]) && $_COOKIE["newsletter"] != "")) {
			$fid = rand(100000,999999);
			echo '<div '.($_POST["scrollspy"] == "true" ? 'data-scrollspy-id="newsletter"' : '').' id="newsletter" class="content-container-div contrast newsletter" data-title="@newsletter/title"><h1 data-translr-value="@newsletter/title"></h1><div class="h-divider"></div><div class="content-div"><span data-translr-value="@newsletter/description"></span><center><div class="newsletter-form"><form id="form-'.$fid.'" action="javascript:sendAJAXForm('.$fid.');" data-action="newsletter.php?action=send" method="POST" data-type="newsletter"><table style="width:100%"><tr><td><input type="email" class="form-input" id="f-'.$fid.'-email" data-translr-placeholder="@newsletter/email-placeholder" required /></td><td><button class="btn" type="submit" data-translr-value="@newsletter/send"></button></form></table></div></div></div>';
		}
	}
?>