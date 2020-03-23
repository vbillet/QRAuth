<?php
class Fields {
    private $fields=Array();
    private $fieldIdx=0;
    private $fieldCount=0;
    private $parentClass=null;
    public function __construct($parentClass){
        $this->parentClass=$parentClass;
    }
    public function getParentClass() { return $this->parentClass; }
    public function get() { return $this->fields; }
    public function getByName($fieldName) {
        foreach ($this->fields as $field)
            if ($field->fieldName == $fieldName) return $field;
            return null;
    }
    public function addField($newField) { 
        foreach ($this->fields as $field)
            if ($field->fieldName == $newField->fieldName) throw console::Error("Field ".$newField->fieldName." already exists.");
        $this->fields[$this->fieldIdx] = $newField;
        $this->fieldIdx++;
        $this->fieldCount++;
    }
    public function getCount() { return $this->fieldCount; }
    public function removeField($fieldName) {
        foreach ($this->fields as $key=>$field)
            if ($field->fieldName == $fieldName){
                unset($this->fields[$key]);
                $this->fieldCount--;
                return;
            }
    }
}
?>