<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;
use TBC\IMK\BaseController;

class APIProviders extends BaseController {
    public function __construct(){
        parent::__construct();
    }

    protected function request ($url , $data = []){
        $response = wp_remote_get( $url );
        $bodyResponse = '';
        if ( is_array( $response ) ) {
            $response = (object) $response;
        }
        if( isset( $response->body ) ) {
            if( gettype( $response->body ) == 'string' ){
                $JSONResponse = json_decode( $response->body );
                if( isset($JSONResponse->success) ){
                    $bodyResponse = $JSONResponse->data;
                } else {
                    return $JSONResponse;
                }
            }
        }
        return $bodyResponse;
    }

    protected function postRequest ($url , $data = []){

        $response = wp_remote_post( $url,
            array(
                'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
                'body'      => json_encode($data),
                'method'    => 'POST'
            )
        );
        $bodyResponse = '';
        if ( is_array( $response ) ) {
            $response = (object) $response;
        }
        if( isset( $response->body ) ) {
            if( gettype( $response->body ) == 'string' ){
                $JSONResponse = json_decode( $response->body );
//                print_r($JSONResponse);die;
                if( $JSONResponse->success ){
                    $bodyResponse = $JSONResponse->data;
                }
            }
        }
        return $bodyResponse;
    }
}
?>