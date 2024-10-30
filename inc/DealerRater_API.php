<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;
use TBC\IMK\APIProviders;

class DealerRater_API extends APIProviders {
    public function __construct(){
        parent::__construct();
        $this->dealerRater = $this->get_active_user_detail($this->APP_DEALER);
    }

    /*
     * @return Array
     * */
    function getReviews( $data ){

        $params = [];

        if( isset( $data['limit'] ) && !empty( $data['limit'] ) ){
            $params['limit'] = $data['limit'] ;
        } else {
            $params['limit'] = 20;
        }

        if( isset( $data['skip'] ) && !empty( $data['skip'] ) ){
            $params['offset'] = $data['skip'] ;
        }

        $queryStr = '';
        if(count($params)){
            $queryStr = "&".http_build_query($params);
        }
        $url = $this->dealerRater->api_url . '/reviews/' . $this->dealerRater->user_id .'?t=json&accessToken=' . $this->dealerRater->api_key;
        return $this->request($url.$queryStr);
    }
}
?>