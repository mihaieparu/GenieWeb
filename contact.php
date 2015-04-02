<?php
	if (isset($_GET["action"]) && $_GET["action"] == "send") {
		if (isset($_POST["email_to"]) && isset($_POST["email"]) && isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["subject"])) {
			$to = $_POST["email_to"];
			$subject = "[HAUFE Contact form] ".$_POST["subject"];
			$random_hash = md5(date('r', time())); 
			$headers = "From: ".$_POST["email"]."\r\nReply-To: ".$_POST["email"];
			$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";
			$attachment = (isset($_FILES["file"]) && $_FILES["file"]["name"] != "" ? chunk_split(base64_encode(file_get_contents($_FILES["file"]["tmp_name"]))) : "");
			ob_start();
			echo '--PHP-mixed-'.$random_hash.'  
			Content-Type: multipart/alternative; boundary="PHP-alt-'.$random_hash.'" 
			
			--PHP-alt-'.$random_hash.'  
			Content-Type: text/plain; charset="iso-8859-1" 
			Content-Transfer-Encoding: 7bit
			
			';
			echo $_POST["message"]."\n\nThis message was sent from the HAUFE Lexware Romania website at ".date("d.m.Y H:i");
		echo '
		--PHP-alt-'.$random_hash.' ';
			if (isset($_FILES["file"]) && $_FILES["file"]["name"] != "") {
				echo '--PHP-mixed-'.$random_hash.'
				Content-Type: application/unknown';
				echo '; name="'.$_FILES["file"]["name"]."  
				Content-Transfer-Encoding: base64  
				Content-Disposition: attachment  
				
				".$attachment. "
				--PHP-mixed-".$random_hash."-- ";
		   }
			$message = ob_get_clean();
			echo mail( $to, $subject, $message, $headers );
		}
	}
	else if ((isset($contact) && $contact != "") || (isset($_POST["custom_contact"]) && $_POST["custom_contact"] != "")) {
		$contact = json_decode((isset($contact) ? $contact : $_POST["custom_contact"]), true);
		if (count($contact)) {
			if (array_key_exists("email", $contact)) {
				$fid = rand(100000,999999);
				echo '<div class="contact-form"><center>'.(array_key_exists("custom_h1", $contact) ? '<h1 style="margin-top:0"'.(strpos($contact["custom_h1"], "@") === 0 ? ' data-translr-value="'.$contact["custom_h1"].'">' : '>'.$contact["custom_h1"]).'</h1>' : '').'<form id="form-'.$fid.'" action="javascript:sendAJAXForm('.$fid.');" data-action="contact.php?action=send"'.(array_key_exists("fileupload", $contact) && $contact["fileupload"] == "1" ? ' data-file-upload="true"' : '').' data-email="'.$contact["email"].'" method="POST" enctype="multipart/form-data" data-type="contact"><table><tr><td><label for="f-'.$fid.'-name" data-translr-value="@contact/name"></label></td><td><input type="text" id="f-'.$fid.'-name" class="form-control" name="name" data-translr-placeholder="@contact/name-placeholder" required /></td></tr><tr><td><label for="f-'.$fid.'-email" data-translr-value="@contact/email"></label></td><td><input type="email" id="f-'.$fid.'-email" class="form-control" data-translr-placeholder="@contact/email-placeholder" required /></td></tr><tr><td><label for="f-'.$fid.'-subject" data-translr-value="@contact/subject"></label></td><td><input type="text" id="f-'.$fid.'-subject" class="form-control" data-translr-placeholder="@contact/subject-placeholder"'.(array_key_exists("custom_subject", $contact) ? ' value="'.$contact["custom_subject"].'"' : '').' /></td></tr>'.(array_key_exists("fileupload", $contact) && $contact["fileupload"] == "1" ? '<tr><td><label for="f-'.$fid.'-file">'.(array_key_exists("file_label", $contact) ? $contact["file_label"] : 'File').'</label></td><td><input type="file" id="f-'.$fid.'-file" name="file" class="form-control" /></td></tr>' : '').'<tr><td><label for="f-'.$fid.'-message" data-translr-value="@contact/message"></label></td><td><textarea class="form-control" data-translr-placeholder="@contact/message-placeholder" id="f-'.$fid.'-message" required></textarea></td></tr></table><button class="btn" type="submit" data-hover-to="black" data-translr-value="@contact/send"></button></form></center></div>';
			}
		}
	}
	else if (isset($_POST["scrollspy"]) && isset($_POST["id"]) && isset($_POST["email"]) && $_POST["scrollspy"] && $_POST["id"] != "" && $_POST["email"] != "") {
		$fid = rand(100000,999999);
		echo '<div '.($_POST["scrollspy"] == "true" ? 'data-scrollspy-id="contact"' : '').' id="contact" class="content-container-div contrast contact" data-title="@contact/title"><h1 data-translr-value="@contact/title"></h1><div class="h-divider"></div><div class="content-div"><span data-translr-value="@contact/description"></span><center><div class="contact-form"><form id="form-'.$fid.'" action="javascript:sendAJAXForm('.$fid.');" data-action="contact.php?action=send" data-email="'.$_POST["email"].'" method="POST" data-type="contact"><table><tr><td><label for="f-'.$fid.'-name" data-translr-value="@contact/name"></label></td><td><input type="text" id="f-'.$fid.'-name" class="form-control" name="name" data-translr-placeholder="@contact/name-placeholder" required /></td></tr><tr><td><label for="f-'.$fid.'-email" data-translr-value="@contact/email"></label></td><td><input type="email" id="f-'.$fid.'-email" class="form-control" data-translr-placeholder="@contact/email-placeholder" required /></td></tr><tr><td><label for="f-'.$fid.'-subject" data-translr-value="@contact/subject"></label></td><td><input type="text" id="f-'.$fid.'-subject" class="form-control" data-translr-placeholder="@contact/subject-placeholder" /></td></tr><tr><td><label for="f-'.$fid.'-message" data-translr-value="@contact/message"></label></td><td><textarea id="f-'.$fid.'-message" class="form-control" data-translr-placeholder="@contact/message-placeholder" required></textarea></td></tr></table><button class="btn" type="submit" data-translr-value="@contact/send" data-hover-to="white" data-hover-into="black"></button></form></div></center><div class="follow" data-center-x="true" id="follow"><h2 data-translr-value="@share/title"></h2><div class="h-divider"></div><a href="https://www.facebook.com/Haufe.Lexware" title="Facebook" target="_blank"><span class="fa fa-fw fa-facebook fa-2x"></span></a><a href="https://twitter.com/HaufeLexwareRO" title="Twitter" target="_blank"><span class="fa fa-fw fa-twitter fa-2x"></span></a><a href="https://www.linkedin.com/company/1047492" title="LinkedIn" target="_blank"><span class="fa fa-fw fa-linkedin fa-2x"></span></a></div></div>';
	}
?>