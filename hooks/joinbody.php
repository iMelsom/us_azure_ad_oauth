<?php
if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted 
require_once '../users/init.php';
global $settings, $user, $db, $apple_login, $us_url_root, $abs_us_root;
if($settings->aao_login==1 && !$user->isLoggedIn()){
  require_once $abs_us_root.$us_url_root.'usersc/plugins/azure_ad_oauth/assets/aao_oauth_login.php';
}
?>
