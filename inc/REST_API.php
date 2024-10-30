<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;
use TBC\IMK\IMKAPI;
class REST_API extends IMKAPI{
    function __construct(){
        parent::__construct();
        add_action( 'rest_api_init', array( $this, 'register_router' ) );
    }

    function register_router(){
        register_rest_route( 'imk/api/v1', '/getInventory', array(
            'methods' => 'GET',
            'callback' => array( $this, 'getInventory' ),
        ) );


        register_rest_route( 'imk/api/v1', '/modelList', array(
            'methods' => 'GET',
            'callback' => array( $this, 'modelList' ),
        ) );

        register_rest_route( 'dealer-rater/api/v1', '/reviews', array(
            'methods' => 'GET',
            'callback' => array( $this, 'dealerRaterReviews' ),
        ) );
    }

    function getInventory( \WP_REST_Request $request ){
        $params = $request->get_query_params();
        $limit = 100;
        if( isset( $params['limit'] ) ){
            $limit =  $params['limit'];
        }

        $filters = ['limit'=> $limit];

        if( isset( $params['skip'] ) ){
            $filters['skip'] =  $params['skip'];
        }

        $inventoryRecords = $this->coreInventory( $filters  );
        return $inventoryRecords;
    }

    function modelList( \WP_REST_Request $request ){
        $params = $request->get_query_params();
        $inventoryRecords = $this->inventoryModelList( $params );
        return $inventoryRecords;
    }

    function dealerRaterReviews( \WP_REST_Request $request ){
        $DealerRater = new DealerRater_API();
        $params = $request->get_query_params();
        $d = $DealerRater->getReviews( $params );
        return $d;
    }
}