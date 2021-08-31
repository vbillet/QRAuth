<?php
require_once "WSClientKey.php";

class WSAuthServer extends WebSocketApplication{
	protected static $instance;
	private $server;
	private $div;
	function __construct()
	{
		parent::__construct();
		WebSocketApplicationConnection::get()->RegisterApplication($this);
		$this->server = WebSocketServer::get();
		$this->server->setCryptographicProtocol(new CryptoNone());
	}
	
	function onServerStarted(WebSocketServer $server)
	{
		console::Log("Auth WebSocket Server started");
		$this->div = file_get_contents("/home/data/div.html");
		$this->div =str_replace("\t","",$this->div);
		$this->div =str_replace("\r","",$this->div);
		$this->div =str_replace("\n","",$this->div);
	}

	function onServerRestart(WebSocketServer $server)
	{
		
	}
	function onServerStopped(WebSocketServer $server)
	{
		
	}
	function onClientConnection(clientConnection $client)
	{
		console::Log("Connection : ".$client->getIP());
	}
	function onClientDeconnection(clientConnection $client)
	{
		console::Log("Deconnection : ".$client->getIP());
		$clientgroup = WebSocketServer::get()->getClientGroup();
		foreach($clientgroup->getChilds() as $cl) {
			console::Log($cl->getID()." : ".$cl->getIP());
		}
	}
	function onClientMessage(clientConnection $client, $Message)
	{
		try {
			$json = json_decode($Message);
		} catch (Exception $e){
			WebSocketServer::disconnect($client);
		}
		// Messages du navigateur web
		if (isset($json->message)){
			WebSocketServer::send($client,'{"pong":"'.$json->message.'"}');
		}
		if (isset($json->box)){
			console::Log($json->box);
			$ck = new WSClientKey();
			$client->add($ck);
			$ck->setToken($json->box);
			$ck->dump();
			WebSocketServer::send($client,$this->div);
		}
		if (isset($json->mobile)){
			console::Log($json->mobile);
			$token = substr($json->mobile,0,32);
			$cl = $this->getClient($token);
			WebSocketServer::send($client,'{"response":"OK"}');
			WebSocketServer::send($cl,'{"response":"OK"}');
		}

		//WebSocketServer::send($client,$client->getIP()." ".$client->getID());
	}
	private function getClient($token){
		$cg = WebSocketServer::getClientGroup()->getChilds();
		foreach ($cg as $cli){
			$wskey = $cli->getChilds()[0];
			if ($wskey->getToken() == $token){
				return $cli;
			}
		}
	}
}
