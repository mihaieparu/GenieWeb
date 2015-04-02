<?php
	if (isset($_POST["ajaxrequest"]) && $_POST["ajaxrequest"] == "true") {
		include("connect.php");
		error_reporting(0);
		$res = retResult("timeline", "ORDER BY Date ASC");
		if ($res) {
			echo '<div class="timeline">';
			$alignment = "left";
			while ($row = mysqli_fetch_array($res)) {
				echo '<div class="timeline-el '.$alignment.'" id="timeline-el-'.$row["ID"].'" data-month="'.date('m', $row["Date"]).'" data-year="'.date('Y', $row["Date"]).'"><div class="timeline-arrow"></div><div class="timeline-circle" title="'.date('m.Y', $row["Date"]).'"></div><div class="timeline-content"><h1'.(strpos($row["Title"], "@") === 0 ? ' data-translr-value="'.$row["Title"].'">' : '>'.$row["Title"]).'</h1><span class="date"><span data-translr-value="@defaults/month-'.date('m', $row["Date"]).'"></span> '.date('Y', $row["Date"]).'</span></br><span class="content"'.(strpos(base64_decode($row["Content"]), "@") === 0 ? ' data-translr-value="'.base64_decode($row["Content"]).'">' : '>'.base64_decode($row["Content"])).'</span></div></div>';
				$alignment = ($alignment == "left" ? "right" : "left");
			}
			echo '</div><script>updateTranslr(); animateTimeline();</script>';
		}
		else {
			echo 'We encountered an error while connecting to our database. Please try again later.';
		}
		mysqli_close($con);
	}
	else if (isset($_POST["scrollspy"]) && isset($_POST["id"]) && $_POST["scrollspy"] && $_POST["id"] != "") {
		echo '<div '.($_POST["scrollspy"] == "true" ? 'data-scrollspy-id="timeline"' : '').' id="timeline" class="content-container-div contrast" data-title="@timeline/title"><h1 data-translr-value="@timeline/title"></h1><div class="h-divider"></div><div class="content-div"><span data-translr-value="@timeline/description"></span><br /><br /><button class="btn" onclick="javascript:loadAJAXTimeline();" data-translr-value="@timeline/show-button"></button><script>function loadAJAXTimeline() {$.ajax({type: "POST", url: ign_pathname + "timeline.php", data: "ajaxrequest=true", success: function(result) { $("#timeline").children(".content-div").children(".btn").animate({opacity:0}, function() { $("#timeline").children(".content-div").children(".btn").remove(); $("#timeline .content-div").append(result); scrollToEl("#timeline"); }); }}); }</script></div></div>';
	}
	else {
		echo 'Bad request. Please try again.';
	}
?>