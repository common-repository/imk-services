<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK;
use TBC\IMK\APIProviders;
use TBC\IMK\Admin\AdminDashboard;
class IMKAPI extends APIProviders{
    public $imkData ;
    public function __construct(){
        parent::__construct();
        $this->imkData = $this->get_active_user_detail($this->APP_IMK);
    }

    /* @param { keyword, limit, skip, month, year, category } */
    function coreBlogs($params=[]){
        try{
            $posts = [];
            $queryStr = '';
            if(count($params)){
                $queryStr = "?".http_build_query($params);
            }
            $url = $this->imkData->api_url. "/api/v1/posts/". $this->imkData->user_id . "/". $this->imkData->group_id.$queryStr;
            $res = $this->request( $url );
            if ( $res && count($res->posts) ){
                $posts = $res->posts;
            }

            return $posts;
        } catch ( Exception $e) {
            print_r($e);
            return [];
        }
    }

    function getSingleBlog($postName){

        $postslug = explode('-',$postName);
        $postId = end($postslug);

        $url = $this->imkData->api_url. "/api/v1/post/".$postId."/". $this->imkData->user_id . "/". $this->imkData->group_id;
        $data = $this->request( $url );
        if ( $data && isset( $data->posts ) ){
            return $data->posts;
        } else {
            return [];
        }
    }

    function coreInvestment( $params , $byCategory=false ) {
        try{
            $data = [];
            $data = array_merge($data, $params);
            $dst = array();
            $sfr = array();
            $nnn = array();
            $finalData = [];
            $url = $this->imkData->api_url. "/api/v1/user/getProperties/". $this->imkData->user_id ."/". $this->imkData->group_id ;
            $properties = $this->postRequest( $url, $data );

            if ( $properties && isset( $properties->investments ) ){
                foreach ($properties->investments as $keyinvest => $investment) {
                    $assets = $properties->assets;
                    if(!isset($investment->currency) || $investment->currency == ''){
                        $properties->investments[$keyinvest]->currency = '$';
                    }
                    foreach ( $assets  as $key => $asset) {
                        if($asset->investmentId == $investment->_id && $asset->type == 'image'){
                            if ($asset->assets) {
                                $investment->{'imageUrl'} = $asset->assets[0]->featuredImage;
                            }
                        }
                        if($asset->investmentId == $investment->_id && $asset->type == 'brochure'){
                            if ($asset->assets) {
                                $properties->investments[$keyinvest]->brochureUrl = $asset->assets[0]->originalUrl;
                            }
                        }

                    }

                    if ( $byCategory ) {

                        if ($investment->category == "DST") {
                            if ($investment->type == 'Multifamily') {
                                $investment->priority = 1;
                            } else {
                                $investment->priority = 2;
                            }
                            array_push($dst, $investment);
                        }

                        if ($investment->category == "SFR") {
                            array_push($sfr, $investment);
                        }

                        if ($investment->category == "NNN") {
                            array_push($nnn, $investment);
                        }

                        $sortArray = array();

                        foreach ($dst as $dstProp) {
                            foreach ($dstProp as $key => $value) {
                                if (!isset($sortArray[$key])) {
                                    $sortArray[$key] = array();
                                }
                                $sortArray[$key][] = $value;
                            }
                        }

                        $orderby = "priority"; //change this to whatever key you want from the array

                        array_multisort($sortArray[$orderby],SORT_ASC,$dst);
                        $finalData = ['dst'=>$dst, 'sfr'=> $sfr, 'nnn'=> $nnn ];
                    } else {
                        $finalData = $properties->investments;
                    }


                }

                return $finalData;
            } else {
                return [];
            }
        }catch(Exception $e){
            return [];
        }
    }

    function investmentDetail( $propertyId ) {
        try{
            $data = [];
            $data['featured']= true;
            $url = $this->imkData->api_url. "/api/v1/user/propertyDetail/". $this->imkData->user_id ."/". $this->imkData->group_id. "/". $propertyId ;
            $properties = $this->postRequest( $url, [] );
            if ( $properties && isset( $properties->investments ) ){
                return $properties->investments;
            } else {
                return [];
            }
        }catch(Exception $e){
            return [];
        }
    }

    function coreInventory( $params ){
        try{

            $queryParam = [];

            if( isset($_GET['offer']) && !empty( $_GET['offer'] ) ){
                $params['offer'] = $_GET['offer'];
            }

            if( isset($_GET['keyword']) && !empty( $_GET['keyword'] ) ){
                $params['keyword'] = $_GET['keyword'];
            }

            if( isset($_GET['maxPrice']) && !empty( $_GET['maxPrice'] ) ){
                $params['maxPrice'] = $_GET['maxPrice'];
            }

            if( isset($_GET['minPrice']) && !empty( $_GET['minPrice'] ) ){
                $params['minPrice'] = $_GET['minPrice'];
            }

            if( isset($_GET['year']) && !empty( $_GET['year'] ) ){
                $queryParam['year'] = $_GET['year'];
            }

            if( isset($_GET['makeName']) && !empty( $_GET['makeName'] ) ){
                $queryParam['make'] = $_GET['makeName'];
            }

            if( isset($_GET['model']) && !empty( $_GET['model'] ) ){
                $queryParam['model'] = $_GET['model'];
            }

            if( count( $queryParam ) ){
                $params['query'] = json_encode( $queryParam );
            }
//    print_r($params);die;
            $url = $this->imkData->api_url. '/api/automobile/list?apiKey='.$this->imkData->api_key;
//            $url = 'http://192.168.1.7:3000/api/automobile/list?apiKey=NHJMiJjLJE83rAJW4ArD3XGWaG6djL';
            $inventory = [];
            $queryStr = '';
            if(count($params)){
                $queryStr = "&".http_build_query($params);
            }
//            echo $url.$queryStr;die;
            $res = $this->request( $url.$queryStr );

            if ( $res ){
                $inventory = $res;
            }
            return $inventory;
        } catch ( Exception $e) {
            print_r($e);
            return [];
        }
    }

    function getInventoryMakeList(){
        try{

            $url = $this->imkData->api_url.'/api/automobile/list/make?apiKey='.$this->imkData->api_key;

            $res = $this->request( $url );
            if( $res && isset($res->list) ){
                return $res->list;
            }
            return [];
        } catch ( Exception $e) {
            print_r($e);
            return [];
        }
    }

    function featuredInventory( $filters ){
        try{
            $queryStr = '';
            if(count($filters)){
                $queryStr = "&".http_build_query($filters);
            }
            $url = $this->imkData->api_url. '/api/automobile/featured-list?apiKey='.$this->imkData->api_key.$queryStr;
            $inventory = [];
            $res = $this->request( $url );

            if ( $res ){
                $inventory = $res;
            }
            return $inventory;
        } catch ( Exception $e) {
            print_r($e);
            return [];
        }
    }

    function inventoryModelList( $params=array() ){
        $queryStr = '';
        if(count($params)){
            $queryStr = "&".http_build_query($params);
        }

        $url = $this->imkData->api_url. '/api/automobile/list/model?apiKey='.$this->imkData->api_key . $queryStr;

        $data = $this->request( $url );
        if ( $data ){
            return $data;
        } else {
            return [];
        }
    }

    function getInventoryDetail( $postName ){
        $postslug = explode('-',$postName);
        $postId = end($postslug);
        $url = $this->imkData->api_url. '/api/automobile/info?apiKey='.$this->imkData->api_key. "&id=".$postId;
//        $url = 'http://192.168.1.7:3000/api/automobile/info?apiKey=NHJMiJjLJE83rAJW4ArD3XGWaG6djL&id='.$postId;;
        $data = $this->request( $url );
        if ( $data ){
            if(  $data->year && $data->make && $data->model ) {
                $data->{'title'} = $data->year . " " . $data->make . " " . $data->model;
            }

            return $data;
        } else {
            return [];
        }
    }

    function corePetList( $atts=[] ){
        try{
            
            $limit = 100;
            $params = [];
            $dataToFind = [];
            if( isset( $atts['limit'] ) ){
                $params['limit'] =  $atts['limit'];
            } else {
                $params['limit'] = $limit;
            }

            if( isset( $atts['skip'] ) ){
                $params['skip'] =  $atts['skip'];
            }

            if( isset( $atts['status'] ) ){
                $dataToFind['status'] =  $atts['status'];
            }
            if( count( $dataToFind ) ) {
                $params['dataToFind'] = json_encode($dataToFind);
            }

            $queryStr = '';
            if(count($params)){
                $queryStr = "&".http_build_query($params);
            }

            $url = $this->imkData->api_url. '/api/pet/getAll?apiKey='.$this->imkData->api_key;
            $petList = [];
//            echo $url.$queryStr;die;
            $res = $this->request( $url.$queryStr );

            if ( $res ){
                $petList = $res;
            }
            return $petList;
        } catch ( Exception $e) {
            print_r($e);
            return [];
        }
    }

    function getPetDetail( $petSlug ){
        try{

            $petSlug = explode('-',$petSlug);
            $petId = end($petSlug);

            $url = $this->imkData->api_url. "/api/pet/get/".$petId."?apiKey=".$this->imkData->api_key;
            $data = $this->request( $url );
            if ( $data ){
                $data->{'title'} = $data->breed . " - " . $data->breedType;
                return $data;
            }else {
                return [];
            }
        } catch ( Exception $e) {
            print_r($e);
            return [];
        }
    }
}
