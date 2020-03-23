<?php
interface ICryptography {
    public function isCrypto();
    public function crypt($Message);
    public function decrypt($Message);
}
?>