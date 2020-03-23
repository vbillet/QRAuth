<?php
class OnByteXOR implements ICryptography{
    function __construct() {
        // TODO Initialiser ici le byte
    }
    public function isCrypto() { return true; }
    public function crypt($Message){
        $l = strlen($Message);
        console::Log($l." ".$Message);
        $data="";
        for ($i = 0; $i < $l; $i++) {
            $data .= chr(ord($Message{$i}) ^ 42 );
        }
        console::Log(strlen($data)." ".$data);
        return $data;
    }
    public function decrypt($Message){

    }
}
?>