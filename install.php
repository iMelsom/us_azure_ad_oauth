<?php
require_once("init.php");
//For security purposes, it is MANDATORY that this page be wrapped in the following
//if statement. This prevents remote execution of this code.
if (in_array($user->data()->id, $master_account)){
    include "plugin_info.php";
    
    //all actions should be performed here.
    $pluginCheck = $db->query("SELECT * FROM us_plugins WHERE plugin = ?",array($plugin_name))->count();
    if($pluginCheck > 0){
        err($plugin_name.' has already been installed!');
    }else{
        $fields = array(
            'plugin'=>$plugin_name,
            'status'=>'installed',
        );
        $db->insert('us_plugins',$fields);
        if(!$db->error()) {
            err($plugin_name.' installed');
            logger($user->data()->id,"USPlugins",$plugin_name." installed");
        } else {
            err($plugin_name.' was not installed');
            logger($user->data()->id,"USPlugins","Failed to to install plugin, Error: ".$db->errorString());
        }
    }
    
    $db->query("ALTER TABLE users ADD COLUMN aao_id varchar(255)");
    $db->query("ALTER TABLE users ADD COLUMN aao_active tinyint NOT NULL DEFAULT '0'");
    $db->query("ALTER TABLE settings ADD COLUMN aao_OAUTH_TENANTID varchar(255)");
    $db->query("ALTER TABLE settings ADD COLUMN aao_OAUTH_CLIENTID varchar(255)");
    $db->query("ALTER TABLE settings ADD COLUMN aao_OAUTH_LOGOUT varchar(255)");
    $db->query("ALTER TABLE settings ADD COLUMN aao_OAUTH_SCOPE varchar(255)");
    $db->query("ALTER TABLE settings ADD COLUMN aao_OAUTH_METHOD varchar(255)");
    $db->query("ALTER TABLE settings ADD COLUMN aao_OAUTH_SECRET varchar(255)");
    $db->query("ALTER TABLE settings ADD COLUMN aao_OAUTH_AUTH_CERTFILE  varchar(255)");
    $db->query("ALTER TABLE settings ADD COLUMN aao_OAUTH_AUTH_KEYFILE  varchar(255)");
    $db->query("ALTER TABLE settings ADD COLUMN aao_URL  varchar(255)");
    $db->query("ALTER TABLE settings ADD COLUMN aao_login  int");
    $db->query("CREATE TABLE `us_aao_matches` ( `id` INT NOT NULL AUTO_INCREMENT , `permission` INT NOT NULL , `aao` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
    $db->query("CREATE TABLE `aao_tblAuthSessions` (  `intAuthID` int(11) NOT NULL AUTO_INCREMENT,
                                                  `txtSessionKey` varchar(255) DEFAULT NULL,
                                                  `dtExpires` datetime DEFAULT NULL,
                                                  `txtRedir` varchar(255) DEFAULT NULL,
                                                  `txtRefreshToken` text DEFAULT NULL,
                                                  `txtCodeVerifier` varchar(255) DEFAULT NULL,
                                                  `txtToken` text DEFAULT NULL,
                                                  `txtIDToken` text DEFAULT NULL,
                                                  PRIMARY KEY (`intAuthID`)
                                                )
        ");
    $check = $db->query("SELECT aao_URL FROM settings")->first();
    
    if($check->aao_URL == ''){ //nothing in the settings so put default data
        $fields = array(
            'aao_URL'=>'URL to this website, no trailing slash',
            'aao_OAUTH_TENANTID'=>'TENANT_ID',
            'aao_OAUTH_CLIENTID'=>'CLIENT_ID',
            'aao_OAUTH_LOGOUT'=>'https://login.microsoftonline.com/common/wsfederation?wa=wsignout1.0',
            'aao_OAUTH_SCOPE'=>'openid%20offline_access%20profile%20user.read',
            'aao_OAUTH_METHOD'=>'secret',
            'aao_OAUTH_SECRET'=>'CLIENT_SECRET',
            'aao_OAUTH_AUTH_CERTFILE'=>'/path/to/certificate.crt',
            'aao_OAUTH_AUTH_KEYFILE'=>'/path/to/privatekey.pem',
            'aao_login'=>1
        );
        $db->update('settings',1,$fields);
        
    }
    //do you want to inject your plugin in the middle of core UserSpice pages?
    //visit https://userspice.com/plugin-hooks/ to get a better understanding of hooks
    $hooks = [];
    
    //The format is $hooks['userspicepage.php']['position'] = path to filename to include
    //Note you can include the same filename on multiple pages if that makes sense;
    //postion options are post,body,form,bottom
    //See documentation for more information
    // $hooks['login.php']['body'] = 'hooks/loginbody.php';
    $hooks['login.php']['form'] = 'hooks/loginbottom.php';
    $hooks['login.php']['bottom'] = 'hooks/loginbottom.php';
    $hooks['join.php']['body'] = 'hooks/joinbody.php';
    $hooks['logout']['body'] = 'hooks/logout.php';
    registerHooks($hooks,$plugin_name);
    
} //do not perform actions outside of this statement
