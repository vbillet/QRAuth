<?php
	echo "Loading configuration\n";
	require_once "system/Console/Singleton.php";
	$host = gethostbyname(gethostname());
    class Config extends Singleton {
		protected static $instance = null;

		protected static $Application = "QRAuthServer";
		protected static $dbPath = "db";
		protected static $maxClient = 128;
		protected static $publicIP = "192.168.1.9";
        protected static $HTTPport = "1";
		protected static $WSport = "2";
		protected static $appsFile = "../../data/apps.xml";
        private function getRelativeBaseDir() {
            if (php_sapi_name()=="cli") { return ""; }
            $cnt = mb_substr_count($_SERVER['REQUEST_URI'],'/')-1;
            $result = "";
            for ($ii=0;$ii<$cnt;$ii++) { $result.="../"; }
            return $result;
        }
        public static function getRoot() { 
            if (php_sapi_name()=="cli") { return "../"; }
            return Config::get()->getRelativeBaseDir().Config::$Application."/";
        }
        public static function getEngine() 				{ return null;/*DBEngineXML::get();*/ }
        public static function getDBRoot() 				{ return Config::getRoot().Config::$dbPath."/"; }
		public static function getCLIServerMaxClient() 	{ return Config::$maxClient; }
		public static function getHost()				{ return gethostbyname(gethostname()); }
		public static function getWSPort()				{ return Config::$WSport; }
		public static function getHTTPPort()			{ return Config::$HTTPport; }
		public static function getAppName()				{ return Config::$Application; }
		public static function getAppsFile()			{ return Config::$appsFile; }
		public static function getPublicIP()			{ return Config::$publicIP; }
    }
?>
