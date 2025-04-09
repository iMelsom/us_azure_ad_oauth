<?php
/* index.php Sample homepage for oAuth Demo project
 *
 * Katy Nicholson, last updated 15/11/2021
 *
 * https://github.com/CoasterKaty
 * https://katytech.blog/
 * https://twitter.com/coaster_katy
 *
*/
 


// Load the auth module, this will redirect us to login if we aren't already logged in.
include 'inc/auth.php';
$Auth = new modAuth();
include 'inc/graph.php';
$Graph = new modGraph();
//Display the username, logout link and a list of attributes returned by Azure AD.
$photo = $Graph->getPhoto();
$profile = $Graph->getProfile();

$aao_email = $profile->mail;
$aao_id    = $profile->id;
$whereNext   = isset($whereNext) ? $whereNext : '/';

$checkExistingQ = $db->query("SELECT * FROM users WHERE email = ?",array ($aao_email));

$CEQCount = $checkExistingQ->count();

//Existing UserSpice User Found
if ($CEQCount>0){
    $checkExisting = $checkExistingQ->first();
    $newLoginCount = $checkExisting->logins+1;
    $newLastLogin = date("Y-m-d H:i:s");
    
    $fields=array('oauth_uid'=>$aao_id, 'oauth_provider'=>'EntraID', 'logins'=>$newLoginCount, 'last_login'=>$newLastLogin);
    
    $db->update('users',$checkExisting->id,$fields);
    $sessionName = Config::get('session/session_name');
    Session::put($sessionName, $checkExisting->id);
    
    $hooks = getMyHooks(['page'=>'loginSuccess']);
    includeHook($hooks,'body');
    
    $ip = ipCheck();
    $q = $db->query("SELECT id FROM us_ip_list WHERE ip = ?",array($ip));
    $c = $q->count();
    if($c < 1){
        $db->insert('us_ip_list', array(
            'user_id' => $checkExisting->id,
            'ip' => $ip,
        ));
    }else{
        $f = $q->first();
        $db->update('us_ip_list',$f->id, array(
            'user_id' => $checkExisting->id,
            'ip' => $ip,
        ));
    }
    
    Redirect::to($whereNext);
    
}else{
    if($settings->registration==0) {
        session_destroy();
        Redirect::to($us_url_root.'users/join.php');
        die();
    } else {
        // //No Existing UserSpice User Found
        // if ($CEQCount<0){
        $date = date("Y-m-d H:i:s");
        $aao_fname = $profile->givenName;
        $aao_lname = $profile->surname;
        if($settings->auto_assign_un==1) {
            $username=NULL;
        } else {
            $username=$aao_email;
        }
        $fields=array('email'=>$aao_email,'username'=>$username,'fname'=>$aao_fname,'lname'=>$aao_lname,'permissions'=>1,'logins'=>1,'join_date'=>$date,'last_login'=>$date,'email_verified'=>1,'password'=>NULL,'oauth_uid'=>$aao_id, 'oauth_provider'=>'EntraID');
        
        $db->insert('users',$fields);
        $theNewId = $db->lastId();
        
        $insert2 = $db->query("INSERT INTO user_permission_matches SET user_id = $theNewId, permission_id = 1");
        
        $ip = ipCheck();
        $q = $db->query("SELECT id FROM us_ip_list WHERE ip = ?",array($ip));
        $c = $q->count();
        if($c < 1){
            $db->insert('us_ip_list', array(
                'user_id' => $theNewId,
                'ip' => $ip,
            ));
        }else{
            $f = $q->first();
            $db->update('us_ip_list',$f->id, array(
                'user_id' => $theNewId,
                'ip' => $ip,
            ));
        }
        include($abs_us_root.$us_url_root.'usersc/scripts/during_user_creation.php');
        
        $sessionName = Config::get('session/session_name');
        Session::put($sessionName, $theNewId);
        Redirect::to($whereNext);
        }
    }
    
    


?>


