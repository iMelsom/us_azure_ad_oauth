<?php if(!in_array($user->data()->id,$master_account)){ Redirect::to($us_url_root.'users/admin.php');} //only allow master accounts to manage plugins! ?>

<?php
include "plugin_info.php";
pluginActive($plugin_name);
if(!empty($_POST['plugin_aao_login'])){ //aao -> Azure AD oAuth
   if(!Token::check(Input::get('csrf'))){
     include($abs_us_root.$us_url_root.'usersc/scripts/token_error.php');
   }
 }
 // oAuth details
 // TENANTID: Your tenant's ID if you set up the app reg as single tenant, otherwise 'common'
 
 //Scope needs to include the services you wish to access - at a minimum you'll need openid and offline_access for logging on to work. Add in user.read if you want to query user profile via Graph. Separate with %20.
 
 define('_OAUTH_TENANTID', 'TENANT_ID');
 define('_OAUTH_CLIENTID', 'CLIENT_ID');
 define('_OAUTH_LOGOUT', 'https://login.microsoftonline.com/common/wsfederation?wa=wsignout1.0');
 define('_OAUTH_SCOPE', 'openid%20offline_access%20profile%20user.read');
 
 // Define either the client secret, or the client certificate details
 // method = 'certificate' or 'secret'
 
 define('_OAUTH_METHOD', 'secret');
 define('_OAUTH_SECRET', 'CLIENT_SECRET');
 //define('_OAUTH_AUTH_CERTFILE', '/path/to/certificate.crt');
 //define('_OAUTH_AUTH_KEYFILE', '/path/to/privatekey.pem');
 
 // on Windows, the certificate paths should be in the form c:/path/to/cert.crt
 
 
 // URL to this website, no trailing slash.
 define('_URL', 'URL to this website, no trailing slash.');
 ?>

<div class="content mt-3">
 		<div class="row">
     <div class="col-6">
       <h2>LDAP Settings</h2>
     <div class="form-group">
       <label for="AAP_server">URL to this website, no trailing slash</label>
       <input type="text" autocomplete="off" class="form-control ajxtxt" data-desc="This Server URI" name="aao_URL" id="aao_URL" value="<?=$settings->aao_URL?>">
     </div>

     <div class="form-group">
       <label for="aao_OAUTH_TENANTID">TENANTID*</label>
       <input type="text" autocomplete="off" class="form-control ajxtxt" data-desc="oAuth TennantID" name="aao_OAUTH_TENANTID" id="aao_OAUTH_TENANTID" value="<?=$settings->aao_OAUTH_TENANTID?>">
     </div>

     <div class="form-group">
       <label for="aao_OAUTH_CLIENTID">ClientID*</label>
       <input type="text" autocomplete="off" class="form-control ajxtxt" data-desc="oAuth ClientID" name="aao_OAUTH_CLIENTID" id="aao_OAUTH_CLIENTID" value="<?=$settings->aao_OAUTH_CLIENTID?>">
     </div>

     <div class="form-group">
       <label for="aao_OAUTH_METHOD">oAuth method**</label>
       <input type="text" autocomplete="off" class="form-control ajxtxt" data-desc="oAuth method" name="aao_OAUTH_METHOD" id="aao_OAUTH_METHOD" value="<?=$settings->aao_OAUTH_METHOD?>">
     </div>

     <div class="form-group">
       <label for="aao_OAUTH_SECRET">oAuth Secret*</label>
       <input type="password" autocomplete="off" class="form-control ajxtxt" data-desc="oAuth Secret" name="aao_OAUTH_SECRET" id="aao_OAUTH_SECRET" value="<?=$settings->aao_OAUTH_SECRET?>">
     </div>

     <div class="form-group">
       <label for="aao_OAUTH_AUTH_CERTFILE">oAuth certfile location**</label>
       <input type="text" autocomplete="off" class="form-control ajxtxt" data-desc="oAuth Ccertfile location" name="aao_OAUTH_AUTH_CERTFILE" id="aao_OAUTH_AUTH_CERTFILE" value="<?=$settings->aao_OAUTH_AUTH_CERTFILE?>">
     </div>

     <div class="form-group">
       <label for="aao_OAUTH_AUTH_KEYFILE">oAuth keyfile location**</label>
       <input type="text" autocomplete="off" class="form-control ajxtxt" data-desc="oAuth keyfile location" name="ldap_version" id="aao_OAUTH_AUTH_KEYFILE" value="<?=$settings->aao_OAUTH_AUTH_KEYFILE?>">
     </div>

 </div>
 <div class="col-6">
    <h2>Important Notes</h2>
   *.  This plugin requires configuration donw in your Microsoft Entra ID instance. <a href="https://katystech.blog/projects/php-azuread-oauth-login" target="_new">See this post by Katy Nicholson for instructions</a>.<br>
   Information for TenantID, ClientID, and oAuth secret can be found in your EntraID app registration. Be adviced that information for oAuth secret is only shown once after creating. If you this information, you need to create a new secret in your EntraID app registration<br><br>
   **.  Method is either using oAuth secret from EntraID app configuration (secret), or certificate (certificate).If either is used, config for the other is made redundant. For information about oAuth secret, se above remark*<br><br>
 </div>
    </div>



    <!-- Do not close the content mt-3 div in this file -->
