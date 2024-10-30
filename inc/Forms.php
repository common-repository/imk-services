<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;
use TBC\IMK\Admin\FormBuilder;
class Forms extends BaseController {
    function __construct(){
        parent::__construct();
    }

    function register(){
        add_shortcode( 'IMK_contact_form_2', array( $this, 'contactForm2' ));
        add_shortcode('IMK_form', 'imk_form');
        add_shortcode( 'IMK_contact_form', array( $this, 'contactForm' ));
        add_shortcode( 'IMK_trade_appraisal_form', array( $this, 'tradeAppraisalForm' ));
    }

    function imk_form($atts = [], $content = null){
        $content = do_shortcode($content);
        return $content;
    }

    function contactForm2(){
        $formObject = [
            "method"=>"post",
            "fields" => [
                ['name'=>"first_name", "type"=>"text", "required"=> true, "validation"=> "required", "placeholder"=>"Enter Name", "class"=> "form-control" ],
                "email_id",
                'phone_number',
                'subject',
                'message',
                ['name'=>'submit', "type"=>'submit', "class"=>"btn btn-priamry", "label"=>false ]],
            "name" => "imk-contact-form",
            "id" => "imk-contact-form"
        ];
        $formBuilder = new FormBuilder( $formObject );
        return $formBuilder->build();
    }

    function contactForm(){
        ob_start();
        include( $this->plugin_path . 'inc/template/forms/contact-form.php');
        $content = ob_get_clean();
        return $content;
    }

    function tradeAppraisalForm(){
        ob_start();
        include( $this->plugin_path . 'inc/template/forms/trade-appraisal.php');
        return ob_get_clean();
    }
}

?>