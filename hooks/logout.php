<?php
if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted
require_once '../users/init.php';
global $settings, $user, $db, $authorize_url, $us_url_root, $abs_us_root;

if($user->data()->aao_active == 1 && $user->isLoggedIn()){
    header('Location:https://' . $_SERVER['SERVER_NAME']. '/usersc/plugins/us_azure_ad_oauthazure_ad_oauth/assets/?action=logout');
    $db->query('UPDATE users SET aao_active = 0 WHERE id = ?', [$user->data()->id]);
    exit;
}
?>
