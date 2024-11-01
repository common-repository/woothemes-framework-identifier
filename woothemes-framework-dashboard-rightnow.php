<?php
/*
	Plugin Name: WooThemes Framework Identifier
	Plugin URI: http://www.sebs-studio.com/blog/woothemes-framework-dashboard-rightnow-detector/
	Description: Displays version of WooThemes Framework in Right Now dashboard widget. If an update is available a link to the framework update page is displayed and the version that is available from WooThemes.
	Author: Sebs Studio (Sebastien)
	Version: 1
	Author URI: http://www.sebs-studio.com
 */

/* WooFramework Version Getter */
function woo_version_checker(){
	$fw_url = 'http://www.woothemes.com/updates/functions-changelog.txt';
	$file_contents = file($fw_url);

	if($file_contents){
		foreach($file_contents as $line_num => $line){
			$current_line = $line;
			if($line_num > 1){	// Not the first or second.
				if(preg_match('/^[0-9]/', $line)){
					$current_line = stristr($current_line,"version");
					$current_line = preg_replace('~[^0-9,.]~','',$current_line);
					$output = $current_line;
					break;
				}
			}
		}
		return $output;
	}
	else{
		return 'Currently Unavailable';
	}
}

/* Framework Version is identified on the Dashboard in the Right Now widget. */
function wooframework_dashboard_rightnow(){
	$localversion = get_option('woo_framework_version');
	$remoteversion = woo_version_checker();
	$localversion = trim(str_replace('.','',$localversion));
	$remoteversion = trim(str_replace('.','',$remoteversion));
	if(strlen($localversion) == 2){ $localversion = $localversion . '0'; }
	if(strlen($remoteversion) == 2){ $remoteversion = $remoteversion . '0'; }
	echo "<div class=\"versions\"><br class=\"clear\">\n";
	if($localversion < $remoteversion){
		$versionstatus = '<span class="b" style="color:red;">(NEED TO UPDATE)</span>';
		$versionavailable = ' | Version available: <span class="b">'.woo_version_checker().'</span> - <a href="admin.php?page=woothemes_framework_update">Update Framework</a>';
	}
	else{ $versionstatus = '<span class="b" style="color:green;">(UP TO DATE)</span>'; }
	echo '<span id="wp-version-message">You are using <span class="b">WooThemes Framework '.$localversion.'</span> '.$versionstatus;
	echo $versionavailable;
	echo "</span>\n";
	echo "</div>\n";
}
add_action('rightnow_end', 'wooframework_dashboard_rightnow');
?>