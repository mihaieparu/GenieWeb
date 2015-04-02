<?php
	include ("connect.php");
	include ("functions.php");
	checkLogin();
	if (isset($_GET["to"]) && $_GET["to"] != "") {
		$res = retResult("newsletter_emails", "");
		$emails = array();
		if ($res) {
			$i = 1;
			while ($row = mysqli_fetch_array($res)) {
				$cem = array($i => $row["Email"]);
				$emails += $cem;
				$i++;
			}
		}
		switch ($_GET["to"]) {
			case "JSON":
				$cem = array("version" => time());
				$emails += $cem;
				echo json_encode($emails);
				break;
			case "XML":
				header('Content-Type: application/xml');
				$domtree = new DOMDocument('1.0', 'UTF-8');
				$xmlRoot = $domtree->createElement("xml");
				$xmlRoot = $domtree->appendChild($xmlRoot);
				$currentList = $domtree->createElement("details");
				$currentList = $xmlRoot->appendChild($currentList);
				$currentList->appendChild($domtree->createElement('version',time()));
				$currentList->appendChild($domtree->createElement('full-version',date('r')));
				$recipientl = $domtree->createElement("recipient-list");
				$recipientl = $xmlRoot->appendChild($recipientl);
				for ($i = 1; $i <= count($emails); $i++) {
					$recipients = $domtree->createElement("recipient");
					$recipients = $recipientl->appendChild($recipients);
					$recipients->appendChild($domtree->createElement('id',$i));
					$recipients->appendChild($domtree->createElement('email',$emails[$i]));
				}
				echo $domtree->saveXML();
				break;
			case "CSV":
				header('Content-Type: text/csv; charset=utf-8');
				header('Content-Disposition: attachment; filename=newsletter_'.time().'.csv');
				$output = fopen('php://output', 'w');
				fwrite($output, "ID,Email\r\n");
				for ($i = 1; $i <= count($emails); $i++) {
					fwrite($output, $i.",".$emails[$i].($i < count($emails) ? "\r\n" : ""));
				}
				fclose($output);
				break;
			case "PLAIN":
				header('Content-Type: text/plain; charset=utf-8');
				header('Content-Disposition: attachment; filename=newsletter_'.time().'.txt');
				$output = fopen('php://output', 'w');
				for ($i = 1; $i <= count($emails); $i++) {
					fwrite($output, $emails[$i].($i < count($emails) ? "\r\n" : ""));
				}
				fclose($output);
				break;
		}
	}
?>