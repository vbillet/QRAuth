<?php

if (defined("__SIMOBJECT")) { return; }
define("__SIMOBJECT",true);

FieldsManager::BEGIN_FIELDS_DECLARATION("SimObject",null);
FieldsManager::DECLARE_FIELD("id",Field::$ftString,32,"");
FieldsManager::DECLARE_FIELD("objectName",Field::$ftString,50,"");
FieldsManager::DECLARE_FIELD("parent",Field::$ftString,50,"");
FieldsManager::END_FIELDS_DECLARATION();

class SimObject{
    protected $id;
    protected $objectName;
    private $parent=null;
    function __construct($guid=null){
        /*$this->fields = new Fields();
        $this->addField(new Field("id",Field::$ftString,32,""));
        $this->addField(new Field("objectName",Field::$ftString,50,""));*/
        if ($guid==null) $guid = console::getGUID($this);
        $this->id = $guid;
        $root = console::getRoot();
        if ($root != null)
            $root->add($this);
    }
    public function __set($pName,$pValue){
        $caller = get_called_class();
        $field = FieldsManager::getField($caller,$pName);
        if ($field!=null){
            $field->set($pValue);
            $this->$pName = $pValue;
        }
    }
    public function addField($field){ $this->fields->addField($field); }
    public function dump(){
        $vars = get_object_vars($this);
        $caller = get_called_class();
        echo $caller."<br/>";
        foreach ($vars as $varname=>$value){
            if (is_object($value)) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;[O] ".$varname." = object<br/>";
            } else {
                $value = $value==null?"NULL":$value;
                if (FieldsManager::getField($caller,$varname)==null)
                {
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;[ ] ".$varname." = ".$value."<br/>";
                } else {
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;[S] ".$varname." = ".$value."<br/>";
                }
            }
        }
        echo "Methods:<br/>";
        $methods = get_class_methods(get_called_class());
        foreach($methods as $meth) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;".$meth."<br/>";
        }
    }
    public function getParent() { return $this->parent; }
    public function setParent($parent) { $this->parent = $parent->getID(); }
    public function setName($pName) { $this->objectName = $pName; }
    public function getName() { return $this->objectName; }
    public function getID() { return $this->id; }
    public function Delete() {
        console::Delete($this->id);
        if ($this->parent!=null)
            console::getById($this->parent)->remove($this);
        //unset($this);
    }
    public function toXML() { 
        $vars = get_object_vars($this);
        $result = "<".get_called_class();
        foreach ($vars as $name=>$value)
        {
            $field = FieldsManager::getField(get_called_class(),$name);
            if(($value!="") && ($value!=null) && ($field!=null))
            {
                $result.= " ". $name."=\"".$value."\"";
            }
        }
        $result.="/>";
        return $result;
    }
    public function toJSON() { 
        $vars = get_object_vars($this);
        $result = "\"".get_called_class()."\":{";
        foreach ($vars as $name=>$value)
        {
            $field = $this->fields->getByName($name);
            if(($value!="") && ($value!=null) && ($field!=null))
            {
                $field->set($value);
                $result.= "\"". $name."\":\"".$value."\",";
            }
        }
        $result = substr($result,0,-1);
        $result.="}";
        return $result;
    }
}
?>