<?php
	if (isset($_POST["attr"]) && $_POST["attr"]) {
		echo '<div class="content-padding">';
		$attr = json_decode($_POST["attr"], true);
		if (array_key_exists("id", $attr)) {
			include ("connect.php");
			$res = retResult("jobs", "WHERE ID='".$attr["id"]."'");
			if ($res) {
				$row = mysqli_fetch_array($res);
				echo '<div class="'.(($row["DateExpire"] > time()) && ($row["Applications"] == "1") ? 'col-lg-8 col-md-6 col-sm-12 col-xs-12' : 'col-lg-12 col-md-12 col-sm-12 col-xs-12').'"><h1 style="margin-top:0"'.(strpos($row["Title"], "@") === 0 ? ' data-translr-value="'.$row["Title"].'">' : '>'.$row["Title"]).'</h1>'.($row["DateExpire"] < time() ? '<h3 class="text-danger" data-translr-value="@jobs/expired"></h2>' : '').'<h3><span data-translr-value="@jobs/ref"></span>: <b>#'.$row["Ref"].'</b></h3><h3><span data-translr-value="@jobs/city"></span>: <b>'.$row["City"].'</b></h3><h3 style="text-align:justify"'.(strpos(base64_decode($row["Description"]), "@") === 0 ? ' data-translr-value="'.base64_decode($row["Description"]).'">' : '>'.base64_decode($row["Description"])).'</h3></div>';
				if (($row["DateExpire"] > time()) && ($row["Applications"] == "1")) {
					echo '<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">';
					$contact = '{"fileupload": "1", "email": "'.$row["ApplicationEmail"].'", "custom_h1": "<span data-translr-value=@jobs/apply-now></span>", "custom_subject": "Ref. #'.$row["Ref"].'"}';
					include ("contact.php");
					echo '</div>';
				}
			}
			else {
				echo 'No jobs found with the ID <b>'.$attr["id"].'</b>. You can go and see <a href="#" data-ajax-go="jobs">all our available jobs</a> or change the ID and then try again.';
			}
			mysqli_close($con);
		}
		else {
			echo 'Bad request. Please try again.';
		}
		echo '</div>';
	}
	else if (isset($_POST["scrollspy"]) && isset($_POST["id"]) && $_POST["scrollspy"] && $_POST["id"] != "") {
		include("connect.php");
		$res = retResult("jobs", "WHERE DateExpire > '".time()."' ORDER BY DateAdded DESC");
		if ($res) {
			echo '<div '.($_POST["scrollspy"] == "true" ? 'data-scrollspy-id="jobs"' : '').' id="jobs" class="content-container-div" data-title="@jobs/title"><h1 data-translr-value="@jobs/title"></h1><div class="h-divider"></div><div class="content-div"><span data-translr-value="@jobs/description"></span><br /><br />';
			for ($i = 0; $i < mysqli_num_rows($res); $i+=4) {
				for ($j = $i; $j < ($i+4 < mysqli_num_rows($res) ? $i+4 : mysqli_num_rows($res)); $j++) {
					$row = mysqli_fetch_array($res);
					echo '<div class="col col-lg-3 col-md-4 col-sm-6 col-xs-12"><div class="job"><h1'.(strpos($row["Title"], "@") === 0 ? ' data-translr-value="'.$row["Title"].'">' : '>'.$row["Title"]).'</h1><div class="h-divider"></div><span class="ref">Ref. <b>#'.$row["Ref"].'</b></span><br /><span class="city"><span class="fa fa-fw fa-map-marker"></span> '.$row["City"].'</span><br /><button class="btn" data-hover-to="white" data-hover-into="rgb(77,157,219)" data-translr-value="@jobs/details" data-ajax-go="job/'.$row["ID"].'"></button></div></div>';
				}
			}
			echo '</div></div>';
		}
		mysqli_close($con);
	}
	else {
		echo 'Bad request. Please try again.';
	}
?>