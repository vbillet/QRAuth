<?php
require_once "Config.php";
function create_token()
{
	srand(time());
	$result="";
	$hex = "0123456789abcdef";
	for ($ii=0;$ii<32;$ii++){
		$rnd = rand(0,15);
		$result.=substr($hex,$rnd,1);
	}
	return $result;
}
if (isset($_REQUEST['app']))
{
	$appID=$_REQUEST['app'];
	if (preg_match('/^[0-9a-f]{32}$/',$appID))
	{
		$conf=Config::get();
		$appsFile = $conf->getAppsFile();
		if (is_file($appsFile))
		{
			$appsXML = file_get_contents($appsFile);
			$apps=new SimpleXMLElement($appsXML);
			$found=false;
			$result='{"QRAuthError":"Unknown appID."}';
			foreach($apps->app as $app){
				if ((string)$app["id"] == $appID){
					$found=true;
					$token=create_token();
					$result='{"QRAuthServer":"'.$conf->getPublicIP().'","QRAuthWSPort":"'.$conf->getWSPort().'","QRAuthToken":"'.$token.'","QRAuthAppName":"'.$app->appname.'"}';
					break;
				}
			}
			echo($result);
		} else {
			echo('{"QRAuthError":"QRAuth Misconfiguration : apps.xml does not exists."}');
		}
	} else {
		echo('{"QRAuthError":"Invalid AppID."}');
	}
} else {
	echo('{"QRAuthError":"AppID not set."}');
}
?>