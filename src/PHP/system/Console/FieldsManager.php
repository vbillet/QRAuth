<?php
class FieldsManager extends Singleton {
    private static $classFields = Array();
    private static $currentClassDefinition = null;
    protected static $instance = null;

    public static function BEGIN_FIELDS_DECLARATION($className,$parentClass){
        if (FieldsManager::$currentClassDefinition!=null)
            throw new Exception("You must end current class definition before starting another one !");
        if (array_key_exists($className,FieldsManager::$classFields))
            throw new Exception($className." Fields are already defined !");
        if ($parentClass!=null)
            if (!array_key_exists($parentClass,FieldsManager::$classFields))
                throw new Exception($className." parent class (".$parentClass." fields are not defined !");
        FieldsManager::$classFields[$className] = new Fields($parentClass);
        FieldsManager::$currentClassDefinition = $className;
    }

    public static function END_FIELDS_DECLARATION(){
        if (FieldsManager::$currentClassDefinition==null)
            throw new Exception("You must begin class field definition before ending it !");
        FieldsManager::$currentClassDefinition = null;
    }

    public static function DECLARE_FIELD($fieldName,$fieldType,$size,$default){
        if (FieldsManager::$currentClassDefinition==null)
            throw new Exception("You must begin class field definition before defining a field !");
        FieldsManager::$classFields[FieldsManager::$currentClassDefinition]->addField(new Field($fieldName,$fieldType,$size,$default));
    }
    public static function getField($className,$fieldName){
        if (!array_key_exists($className,FieldsManager::$classFields))
            throw new Exception("No field definition for class : ".$className." !");
        $flds = FieldsManager::$classFields[$className];
        while (true){
            $fld = $flds->getByName($fieldName);
            if ($fld != null) return $fld;
            $parent = $flds->getParentClass();
            if ($parent!=null) {
                $flds = FieldsManager::$classFields[$parent];
            } else {
                return null;
            }
        }
        
    }
}

?>