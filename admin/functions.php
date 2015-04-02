<?php
	function checkLogin($logout = true) {
		if (isset($_COOKIE["sess_id"]) && isset($_COOKIE["username"]) && $_COOKIE["sess_id"] != "" && $_COOKIE["username"] != "") {
			$res = retResult("users", "WHERE Username='".$_COOKIE["username"]."'");
			if ($res) {
				$row = mysqli_fetch_array($res);
				if ($row["CurrSessID"] == $_COOKIE["sess_id"]) {
					return 1;
				}
				else {
					if ($logout) { logOut(); }
					return 0;
				}
			}
			else {
				if ($logout) { logOut(); }
				return 0;
			}
		}
		else {
			if ($logout) { logOut(); }
			return 0;
		}
	}
	function logOut($updatedb = false) {
		if ($updatedb) {
			if (isset($_COOKIE["username"]) && $_COOKIE["username"] != "") {
				mysqli_query($con, "UPDATE users SET CurrSessID='' WHERE Username='".$_COOKIE["username"]."'");
			}
		}
		setcookie("username", "");
		setcookie("sess_id", "");
		header("Location: login.php?logout=true");
		exit;
	}
	function getUsername() {
		if (checkLogin()) {
			return $_COOKIE["username"];
		}
	}
	function browser_nv_ro(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$browsers = array(
							'Chrome' => array('Google Chrome','Chrome/(.*)\s'),
							'MSIE' => array('Internet Explorer','MSIE\s([0-9\.]*)'),
							'Firefox' => array('Firefox', 'Firefox/([0-9\.]*)'),
							'Safari' => array('Safari', 'Version/([0-9\.]*)'),
							'Opera' => array('Opera', 'Version/([0-9\.]*)')
							); 
							 
		$browser_details = array();
		 
			foreach ($browsers as $browser => $browser_info){
				if (preg_match('@'.$browser.'@i', $user_agent)){
					$browser_details['nume'] = $browser_info[0];
						preg_match('@'.$browser_info[1].'@i', $user_agent, $version);
					$browser_details['versiune'] = $version[1];
						break;
				} else {
					$browser_details['nume'] = 'Necunoscut';
					$browser_details['versiune'] = 'Necunoscut';
				}
			}
		 
		return ($browser_details['nume'].' versiunea '.$browser_details['versiune']);
	}
	function browser_n_ro(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$browsers = array(
							'Chrome' => array('Google Chrome','Chrome/(.*)\s'),
							'MSIE' => array('Internet Explorer','MSIE\s([0-9\.]*)'),
							'Firefox' => array('Firefox', 'Firefox/([0-9\.]*)'),
							'Safari' => array('Safari', 'Version/([0-9\.]*)'),
							'Opera' => array('Opera', 'Version/([0-9\.]*)')
							); 
							 
		$browser_details = array();
		 
			foreach ($browsers as $browser => $browser_info){
				if (preg_match('@'.$browser.'@i', $user_agent)){
					$browser_details['nume'] = $browser_info[0];
						preg_match('@'.$browser_info[1].'@i', $user_agent, $version);
					$browser_details['versiune'] = $version[1];
						break;
				} else {
					$browser_details['nume'] = 'Necunoscut';
					$browser_details['versiune'] = 'Necunoscut';
				}
			}
		 
		return $browser_details['nume'];
	}
	function browser_v_ro(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$browsers = array(
							'Chrome' => array('Google Chrome','Chrome/(.*)\s'),
							'MSIE' => array('Internet Explorer','MSIE\s([0-9\.]*)'),
							'Firefox' => array('Firefox', 'Firefox/([0-9\.]*)'),
							'Safari' => array('Safari', 'Version/([0-9\.]*)'),
							'Opera' => array('Opera', 'Version/([0-9\.]*)')
							); 
							 
		$browser_details = array();
		 
			foreach ($browsers as $browser => $browser_info){
				if (preg_match('@'.$browser.'@i', $user_agent)){
					$browser_details['nume'] = $browser_info[0];
						preg_match('@'.$browser_info[1].'@i', $user_agent, $version);
					$browser_details['versiune'] = $version[1];
						break;
				} else {
					$browser_details['nume'] = 'Necunoscut';
					$browser_details['versiune'] = 'Necunoscut';
				}
			}
		 
		return $browser_details['versiune'];
	}
	function OS() {
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$oses = array (
			'iPhone' => '(iPhone)',
			'Windows 3.11' => 'Win16',
			'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
			'Windows 98' => '(Windows 98)|(Win98)',
			'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
			'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
			'Windows 2003' => '(Windows NT 5.2)',
			'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
			'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
			'Windows 8' => '(Windows NT 6.2)|(Windows 7)',
			'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
			'Windows ME' => 'Windows ME',
			'Open BSD'=>'OpenBSD',
			'Sun OS'=>'SunOS',
			'Linux'=>'(Linux)|(X11)',
			'Safari' => '(Safari)',
			'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
			'QNX'=>'QNX',
			'BeOS'=>'BeOS',
			'OS/2'=>'OS/2',
			'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
		);
	
		foreach($oses as $os=>$pattern){
			if(eregi($pattern, $userAgent)) {
				return $os;
			}
		}
		return 'Necunoscut';
	}
	function checkMobile() {
		$ua = $_SERVER['HTTP_USER_AGENT'];
		$mobile = 0;
		if (stristr($ua, "Windows CE") or stristr($ua, "AvantGo") or stristr($ua,"Mazingo") or stristr($ua, "Mobile") or stristr($ua, "T68") or stristr($ua,"Syncalot") or stristr($ua, "Blazer") ) {$mobile = 1;}
		return $mobile;
	}
?>