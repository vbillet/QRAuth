<?php

class Field {
    public static $ftInteger = 0;
    public static $ftByte = 1;
    public static $ftString = 2;
    public static $ftFloat = 3;
    public static $ftDate = 4;
    public static $ftTime = 5;
    public static $ftDateTime = 6;
    public static $ftBoolean = 7;
    public static $ftMemo = 8;
    public static $ftBlob = 9;
    public static $ftAutoInc = 100;
    public $fieldName;
    public $fieldType;
    public $size;
    public $default;
    private $value;
    function __construct($fieldName,$fieldType,$size,$default){
        $this->fieldName = $fieldName;
        $this->fieldType = $fieldType;
        $this->size = $size;
        $this->default=$default;
    }
    public function init() { $this->value = $this->default; }
    public function set($value){
        if (($this->fieldType == Field::$ftString) || ($this->fieldType == Field::$ftMemo)) {
            $value = str_replace(">","µgtµ",$value);
            $value = str_replace("<","µltµ",$value);
            $value = str_replace("'","µqoµ",$value);
            $value = str_replace("\"","µguµ",$value);
            $value = substr($value,0,$this->size);
        } else
        if (($this->fieldType == Field::$ftInteger) || ($this->fieldType == Field::$ftAutoInc)) {
            if (!is_int($value)) throw console::Error("Field ".$this->fieldName." is an Integer, expect Integer value.");
        } else
        if ($this->fieldType == Field::$ftFloat){
            if (!is_numeric($value)) throw console::Error("Field ".$this->fieldName." is a Float, expect Float value.");
        } else
        if ($this->fieldType == Field::$ftBoolean){
            if (!is_bool($value)) throw console::Error("Field ".$this->fieldName." is a Boolean, expect boolean value.");
        } else
        if ($this->fieldType == Field::$ftByte){
            if (!is_int($value)) throw console::Error("Field ".$this->fieldName." is a Byte, expect Byte value.");
            if (($value<0) || ($value>255)) throw console::Error("Field ".$this->fieldName." is a Byte, expect value between 0 and 255.");
        } else
        if ($this->fieldType == Field::$ftDate){
            list($jour,$mois,$annee) =  explode("/",$value);
            $jour = intval($jour);
            $mois = intval($mois);
            $annee = intval($annee);
            if ((!is_int($jour)) || (!is_int($mois)) || (!is_int($annee))) throw console::Error("Field ".$this->fieldName." is a Date, expect date value.");
            if (!checkdate($mois,$jour,$annee)) throw console::Error("Field ".$this->fieldName." is a Date, expect date value.");
        } else
        if ($this->fieldType == Field::$ftTime){
            list($hh,$mm,$ss) =  explode(":",$value);
            $hh = intval($hh);
            $mm = intval($mm);
            $ss = intval($ss);
            if ((!is_int($hh)) || (!is_int($mm)) || (!is_int($ss))) throw console::Error("Field ".$this->fieldName." is a Time, expect time value.");
            if ( ($hh<0) || ($hh<23) || ($mm<0) || ($mm>59) || ($ss<0) || ($ss>59)) throw console::Error("Field ".$this->fieldName." is a Time, expect time value.");
        }
        if ($this->fieldType == Field::$ftDateTime){
            list($dte,$hre) = explode(" ",$value);
            list($jour,$mois,$annee) =  explode("/",$dte);
            $jour = intval($jour);
            $mois = intval($mois);
            $annee = intval($annee);
            if ((!is_int($jour)) || (!is_int($mois)) || (!is_int($annee))) throw console::Error("Field ".$this->fieldName." is a Date, expect date value.");
            if (!checkdate($mois,$jour,$annee)) throw console::Error("Field ".$this->fieldName." is a Date, expect date value.");
            list($hh,$mm,$ss) =  explode(":",$hre);
            $hh = intval($hh);
            $mm = intval($mm);
            $ss = intval($ss);
            if ((!is_int($hh)) || (!is_int($mm)) || (!is_int($ss))) throw console::Error("Field ".$this->fieldName." is a Time, expect time value.");
            if ( ($hh<0) || ($hh<23) || ($mm<0) || ($mm>59) || ($ss<0) || ($ss>59)) throw console::Error("Field ".$this->fieldName." is a Time, expect time value.");
        }
        $this->value = $value;
    }
    public function get(){
        $value = $this->value;
        if (($this->fieldType == Field::$ftString) || ($this->fieldType == Field::$ftMemo)) {
            $value = str_replace("µgtµ", ">" , $value);
            $value = str_replace("µltµ", "<" , $value);
            $value = str_replace("µqoµ", "'" , $value);
            $value = str_replace("µguµ", "\"", $value);
        }
        return $value;
    }
}

?>