<?php

/*
  * @package IMKServicePlugin
  * */

namespace TBC\IMK\Admin;
class FormBuilder{
    public $formName="";
    public $formId="";
    public $formClass="";
    public $formMethod="GET";
    public $formFields = [];
    public $validFields = ["class", "id", "name", "type", "placeholder", "value"];

    function __construct($form){
        try{
            $this->form = $form;
            if(  !$this->form  ){
                throw new Error("Required array");
            }
            if(!count( $this->form['fields'] ) ){
                throw new Error("Invalid Form Object, please provide fields for the form");
            }
            $this->formFields = $this->form['fields'];

            if(  isset( $this->form['method'] ) ){
                $this->formMethod = $this->form['method'];
            }


            if( isset(  $this->form['name'] ) ){
                $this->formName = $this->form['name'];
            }

            if( isset(  $this->form['id'] ) ){
                $this->formId = $this->form['id'];
            }

            if( isset(  $this->form['class'] ) ){
                $this->formClass = $this->form['class'];
            }

        } catch ( Exception $e ){
            throw $e;
        }
    }

    function build(){
        $form = "<form name='".$this->formName."' method='".$this->formMethod."' class='".$this->formClass."' id='".$this->formId."' >";
        foreach($this->form['fields'] as $field ){
            $form.= $this->createInput( $field );
        }

        $form.="</form>";

        return $form;
    }

    private function createInput( $field ){
        $fieldStr='';
        $validFields = $this->validFields;
        if( is_string( $field ) ){
            $fieldStr= "<div class='form-group'> <label>".$this->displayFieldName($field)."</label> <input name='$field' type='text' class='form-control' id='$field' placeholder='".$this->displayFieldName($field)."' /></div>";
        } else if( is_array( $field ) ) {

            if( !isset( $field['name'] ) && empty( $field['name'] ) ) {
                throw new Exception("Invalid field, require name property");
            }
            $wClass = "form-group";
            if ( isset( $field['wrapperClass'] ) ){
                $wClass =  $field['wrapperClass'];
            }
            $fieldStr="<div class='$wClass'>";
            if( isset( $field['wrapper'] ) && is_bool( $field['wrapper'] ) && $field['wrapper'] == false ) {
                $fieldStr="";
            }


            if( isset( $field['label'] ) ){
                if ( is_string( $field['label'] ) && !empty( $field['label'] ) ){
                    $fieldStr.="<label for='".$field['label']."'>".$this->displayFieldName($field['label'])."</label>";
                }
            } else {
                $fieldStr.="<label>".$this->displayFieldName($field['name'])."</label>";
            }
            $fieldStr.="<input ";
            $properties = [];
            foreach( $validFields as $property ){
                if(  isset($field[$property]) ){
                    array_push( $properties, " $property='".$field[$property]."' " );
                }
            }

            if( isset( $field['required'] ) && is_bool($field['required']) && $field['required'] ){
                $fieldStr.=" required ";
            }
            if( isset( $field['validation'] ) && is_string($field['validation']) ){
                $fieldStr.=" data-validation='".$field['validation']."' ";
            }
            $fieldStr.= implode(" ", $properties );

            $fieldStr.="/> ";
            $wrapperClose = "</div>";
            if( isset( $field['wrapper'] ) && is_bool( $field['wrapper'] ) && $field['wrapper'] == false ) {
                $wrapperClose = "";
            }

            $fieldStr.=$wrapperClose;

        } else {
            throw new Exception("Invalid field object");
        }

        return $fieldStr;
    }

    function displayFieldName($str) {
        return ucwords(str_replace("_", " ", $str));
    }
}
?>