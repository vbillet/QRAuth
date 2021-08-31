<?php

FieldsManager::BEGIN_FIELDS_DECLARATION("WSClientKey","SimObject");
FieldsManager::DECLARE_FIELD("token",Field::$ftString,32,"");
FieldsManager::DECLARE_FIELD("phases",Field::$ftString,8192,"");
FieldsManager::DECLARE_FIELD("photons",Field::$ftString,8192,"");
FieldsManager::DECLARE_FIELD("phone",Field::$ftString,32,"");
FieldsManager::DECLARE_FIELD("mail",Field::$ftString,64,"");
FieldsManager::DECLARE_FIELD("userToken",Field::$ftString,32,"");
FieldsManager::END_FIELDS_DECLARATION();

class WSClientKey extends SimObject {
    protected $token;
	protected $ServerPhases;
	protected $ClientPhases;
	protected $photons;
	protected $phone;
	protected $userToken;
	function __construct(){
		parent::__construct();
		$this->createPhases();
		$this->ClientPhases = "";
		$this->photons = "";
		$this->userToken = "";
	}
	public function setToken($token){
		$this->token = $token;
	}
	public function addPhases($phases){
		$this->ClientPhases.=$phases;
	}
	public function addPhotons($photons){
		$this->photons.=$photons;
	}
	private function createPhases(){
		$this->ServerPhases = "";
		for ($ii=0;$ii<4096;$ii++){
			$rnd = rand(0,255);
			$this->ServerPhases.=dechex($rnd);
		}
	}
	public function computeKey(){
		
	}

}