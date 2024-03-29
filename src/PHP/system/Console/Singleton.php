<?php
    /**
     * Classe servant à la création du desing pattern Singleton
     * @author: Vincent BILLET
     * @lastupdate : 2020/01/17
     */
	if (defined("SINGLETON")) { return; }
	define("SINGLETON",true);

	abstract class Singleton {
        function __construct() {
            if (self::isInstancied()) { throw new Exception(get_called_class()." is a Singleton, so you can instanciate it only once."); }
            eval(get_called_class()."::\$instance=\$this;");
        }
        protected static function isInstancied() {
            $b=null;
            eval("\$b=".get_called_class()."::\$instance!=null;");
            return $b;
        }
        public static function get() { 
            $inst=null;
            if (!self::isInstancied()) eval("new ".get_called_class()."();");
            eval("\$inst=".get_called_class()."::\$instance;");
            return $inst;
        }
    }
?>