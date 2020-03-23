<?php
class CryptoNone implements ICryptography{
    public function isCrypto() { return true; }
    public function crypt($Message){ return $Message; }
    public function decrypt($Message){ return $Message; }
}
?>