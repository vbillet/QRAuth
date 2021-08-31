<?php
require_once "Config.php";
require_once "system/console/console.package.php";
require_once "net/WebSocket/WebSocketServer/WebSocketServer.package.php";
require_once "net/CryptoProtocol/ICryptography.php";
require_once "net/CryptoProtocol/cryptoNone/cryptoNone.php";
require_once "net/commands/commands.package.php";
require_once "net/modules/modules.package.php";
require_once "QRAuthServer/WSAuthServer.php";
class QRAuthServer extends Singleton{
	private $WSServer = null;
	protected static $instance;
	public function __construct(){
		parent::__construct();
		$WSServer = WSAuthServer::get();
	}

}
QRAuthServer::get();
?>