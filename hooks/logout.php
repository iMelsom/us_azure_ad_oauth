<?php
if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted 

header('Location: https://ffm3-dev.likeperson.info/usersc/plugins/azure_ad_oauth/assets/?action=logout');
exit;


?>
