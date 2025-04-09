<?php
/* config.inc Configuration for oAuth Demo project
 *
 * Katy Nicholson, last updated 17/11/2021
 *
 * https://github.com/CoasterKaty
 * https://katytech.blog/
 * https://twitter.com/coaster_katy
 *
 

***RENAME TO config.inc ***

*/
// MySQL connection details
if (file_exists('../../../../users/init.php')) require_once '../../../../users/init.php';
else require_once 'users/init.php';
$SQLtemp = explode("; ",$GLOBALS['config']['mysql']['host']);
define('_MYSQL_HOST', $SQLtemp[0]);
define('_MYSQL_USER', $GLOBALS['config']['mysql']['username']);
define('_MYSQL_DB', $GLOBALS['config']['mysql']['db']);
define('_MYSQL_PASS', $GLOBALS['config']['mysql']['password']);


// oAuth details



// TENANTID: Your tenant's ID if you set up the app reg as single tenant, otherwise 'common'

//Scope needs to include the services you wish to access - at a minimum you'll need openid and offline_access for logging on to work. Add in user.read if you want to query user profile via Graph. Separate with %20.

define('_OAUTH_TENANTID', $settings->aao_OAUTH_TENANTID);
define('_OAUTH_CLIENTID', $settings->aao_OAUTH_CLIENTID);
define('_OAUTH_LOGOUT', $settings->aao_OAUTH_LOGOUT);
define('_OAUTH_SCOPE', $settings->aao_OAUTH_SCOPE);

// Define either the client secret, or the client certificate details
// method = 'certificate' or 'secret'

define('_OAUTH_METHOD', $settings->aao_OAUTH_METHOD);

define('_OAUTH_SECRET', $settings->aao_OAUTH_SECRET);
define('_OAUTH_AUTH_CERTFILE', $settings->aao_OAUTH_AUTH_CERTFILE);
define('_OAUTH_AUTH_KEYFILE', $settings->aao_OAUTH_AUTH_KEYFILE);

// on Windows, the certificate paths should be in the form c:/path/to/cert.crt


// URL to this website, no trailing slash.
//define('_URL', $settings->aao_URL . "/usersc/plugins/azure_ad_oauth/assets");
define('_URL', $settings->aao_URL);
?>

