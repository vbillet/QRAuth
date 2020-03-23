<?php
class console extends Singleton{
    protected static $instance = null;
    private $logFile = "undefined.log";
    private $logDir = "log";
    private $guidMap = Array();
    private $isServer = false;
    private static $rootGroup;
    function __construct(){
        parent::__construct();
        $this->logFile = Config::getRoot()."log/console.log";
        $this->logDir = Config::getRoot()."log";
        if (!is_dir($this->logDir)) {
            mkdir(Config::getRoot()."log");
        }
        if (is_file($this->logFile)){
            unlink($this->logFile);
        }
        console::$rootGroup = new SimGroup();
        console::$rootGroup->setName("RootGroup");
        console::Log("UMLWizard v1.0");
    }
    public static function getRoot(){
        return console::$rootGroup;
    }
    private static function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    public static function Log($message) {
        $log = fopen(console::get()->logFile,"a");
        fwrite($log,"L:".console::microtime_float().":".$message."\n");
        fclose($log);
        if (php_sapi_name()=="cli") { echo $message."\n"; }
    }
    public static function Error($message) {
        $log = fopen(console::get()->logFile,"a");
        fwrite($log,"E:".console::microtime_float().":".$message."\n");
        fclose($log);
        if (php_sapi_name()=="cli") { echo "Error : ".$message."\n"; }
        return new Exception($message);
    }
    public static function getGUID($obj) {
        $ok = false;
        $console = console::get();
        while (!$ok){
            $guid = sprintf('%04X%04X%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)); 
            
            if (!array_key_exists($guid,$console->guidMap)) { $ok=true; }
        }
        $console->guidMap[$guid] = $obj;
        return $guid;
    }
    public static function getById($guid) { 
        $console = console::get();
        if (array_key_exists($guid,$console->guidMap)){
            return $console->guidMap[$guid]; 
        } else {
            return null;
        }
        
    }
    public static function Delete($guid) { 
        $console = console::get();
        if (array_key_exists($guid,$console->guidMap)){
            unset($console->guidMap[$guid]);
        }
    }
    public function setServer(){ $this->isServer = true; }
    public function isServer() { return $this->isServer; }
    function getRequest($req) { 
        $r="";
        if (isset($_REQUEST[$req])) { $_SESSION[$req] = $_REQUEST[$req]; }
        if (isset($_SESSION[$req])) { $r = $_SESSION[$req]; }
        return $r; 
    }
}

?>