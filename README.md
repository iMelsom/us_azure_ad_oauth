# Azure AD (Entra ID) oAuth plugin for UserSpice
Plugin is based on PHPAzureADoAuth (https://github.com/CoasterKaty/PHPAzureADoAuth) by @CoasterKaty
Requires the UserSpice framework (https://userspice.com/)

Installation:
Download zipfile, unpack and place in /usersc/plugins
In your userspice instance open Admin Dashboard and Plugin manager to activate plugin

Configuration: 
You need to configure an app registration in your Entra ID console in your Microsoft 365 Tennant. Follow the instrucions here: https://github.com/CoasterKaty/PHPAzureADoAuth
Callbackurl (in Entra ID app registration) : https://{domain name}/usersc/plugins/azure_ad_oauth/assets/oauth.php
Must match the Site url defined in the plugin config: https://{domain name}/usersc/plugins/azure_ad_oauth/assets

Usage: 
Plugin only activates login using an existing Entra ID. In Entra ID you may configure acces through group memebership
Plugin do not actively connect or use groups in Entra ID to match with permission groups in User Spice. 

If you wish to use such permissions you need to both add the nescecary groups and roles in youre application registration in Entra ID, and add the nescecary groups in Userspice. 
The variable $Auth->userRoles will contain all the different groups and roles connected to the user in Entra ID. 

