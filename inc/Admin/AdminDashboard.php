<?php
/*
  * @package IMKServicePlugin
  * */
namespace TBC\IMK\Admin;
use TBC\IMK\BaseController;
class AdminDashboard extends BaseController{
    public $tablename = '';
    public $third_parties_apps = [ ];
    public $table_fields = [ "title" , "user_id", "group_id", "api_key", "api_url", "app_name" ];
    public $form_fields = [
        "IMKService" => [
            "title" , "user_id", "group_id", "api_key", "api_url", ['name'=>'add_app_detail', "value"=>"Set Up", "class"=>"btn btn-primary" , 'type'=> 'submit', 'label'=> false ]
        ],
        "DealerRater" => [
            "title" , "user_id", "api_key", ["name"=> "api_url", "type"=>"hidden", "value"=> "https://api.dealerrater.com", "label"=>false ], ['name'=>'add_app_detail', "value"=>"Set Up", "class"=>"btn btn-primary" ,'type'=> 'submit', 'label'=> false ]
        ]
    ];

	public function __construct(){
	    parent::__construct();
        $this->third_parties_apps= [
            ["title" => "IMK Services", "image" => $this->plugin_url . '/images/apps/imkService.jpg', "slug"=> "IMKService"  ],
            ["title" => "Dealer Rater", "image" => $this->plugin_url . '/images/apps/dealerRater.jpg', "slug"=> "DealerRater" ],
        ];
	}

	function register(){
        add_action("admin_menu" , array( $this,  "addMenu" ) );
        add_action("admin_enqueue_scripts", array( $this , 'init_assets' ) );
    }

	function addMenu(){
		add_menu_page("IMK Dashbaord", "IMK Dashbaord", 4, "imk-dashboard", [$this, "imkDashboard"], $this->plugin_url . 'images/imk-logo.png' , 6);
        add_submenu_page("imk-dashboard", "IMK Integrate Apps","Integrate Apps", 4, "imk-integrate-apps", [$this, "imkIntegratedApps"]);
        add_submenu_page("imk-dashboard", "Setting","Setting", 4, "imk-settings", [$this, "imkSettings"]);
        add_submenu_page("imk-dashboard", "Help","Help", 4, "imk-help", [$this, "imkHelp"]);
	}

	function imkDashboard(){
		include_once( $this->plugin_path. "inc/Admin/dashboard.php" );
	}

	function imkSettings(){
		include_once( $this->plugin_path. "inc/Admin/settings.php" );
	}
	function imkHelp(){
		include_once( $this->plugin_path. "inc/Admin/help.php" );
	}

	function imkIntegratedApps(){
        if( isset( $_POST['add_app_detail'] ) ){
            if( !isset($_POST['title']) || empty($_POST['title']) ){
                echo "<div class='alert alert-danger'> Title is required. </div>";
            } else {
                $isAppCreated = $this->add_app_detail( $_POST );
                if( $isAppCreated ){
                    wp_redirect( admin_url(). "admin.php?page=imk-dashboard");
                    die;
                }
            }
        }
        if( isset($_GET['app']) && !empty($_GET['app']) ){

            $appSlug =  $_GET['app'];
            $newField = ["name"=> "app_name", "value"=> $appSlug, "type"=>"hidden", "label"=>false ];
            $fields = $this->form_fields[$appSlug];
            $fields[] = $newField;
            $formObject = [
                "method"=>"post",
                "fields" => $fields,
                "name" => "app_".$appSlug,
                "id" => "$appSlug"
            ];

            $selectedServices = [];
            foreach ( $this->third_parties_apps as $service ){
                if( $service['slug'] == $appSlug ){
                    $selectedService = $service;
                }
            }

            echo '<h1 class="imk-admin-header">Add '.$selectedService['title'].'</h1>';
            $formBuilder = new FormBuilder( $formObject );
            echo $formBuilder->build();
            die;


        } else {
            $third_parties_apps = $this->third_parties_apps;
            include_once( $this->plugin_path. "inc/Admin/imkIntegratedApps.php" );
        }

    }

	public function init_assets(){
		wp_enqueue_style( 'imk_admin_css',  $this->plugin_url . 'inc/Admin/css/bootstrap.min.css',  false,  $this->version );
	    wp_enqueue_style( 'imk_admin_css2', $this->plugin_url . 'inc/Admin/css/imk-style.css', false,  $this->version);
	    wp_enqueue_script( 'imk_admin_script', $this->plugin_url . 'inc/Admin/scripts/main.js', array('jquery'),  $this->version, true);
	}
	public function add_user_detail_form(){
		return '
			<div class="container-fluid">
				<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
				    <div class="form-group">
				      <label for="detail_title">Title</label>
				      <input type="text" class="form-control" id="detail_title" placeholder="Enter Title" name="detail_title" >
				    </div>

				    <div class="form-group">
				      <label for="user_id">User ID:</label>
				      <input type="text" class="form-control" id="user_id" placeholder="Enter user ID" name="user_id" >
				    </div>
				    <div class="form-group">
				      <label for="groupId">Group ID:</label>
				      <input type="text" class="form-control" id="groupId" placeholder="Enter group ID" name="group_id">
				    </div>
				    
				    <div class="form-group">
				      <label for="api_key">API Key:</label>
				      <input type="text" class="form-control" id="api_key" placeholder="Enter Api Key" name="api_key">
				    </div>

				    <div class="form-group">
				      <label for="api_url">API URL:</label>
				      <input type="text" class="form-control" id="api_url" placeholder="Enter Api url" name="api_url">
				    </div>

				    <div class="form-group">
				      	<input type="submit" class="btn btn-primary btn-sm" name="add_user_detail" value="Add User" >
				    </div>
			  </form>
			</div>
		';
	}
	/*
		@return int
		1: success
	*/
	public function add_app_detail( $data ){
        $data_fields = [];
        $data_count = [];

        foreach ( $this->table_fields as $field ) {
            if( $data[$field] ) {
                $data_fields[$field] = $data[$field];
                array_push( $data_count, "%s" );
            }
        }

        return $this->db->insert( $this->tablename, $data_fields ,  $data_count  );
	}

	public function get_user_detail(){
		$data = $this->db->get_results( "SELECT * FROM " . $this->tablename);

		$imkRecords = [];
		$dealerRecords = [];

		foreach ( $data as $item ) {

		    if( $item->app_name == $this->APP_IMK ){
                array_push( $imkRecords , $item );
            } else {
                array_push( $dealerRecords , $item );
            }
        }

        echo "<div class='container-fluid'><h3>IMK Setups</h3>";
		echo $this->buildTable( $imkRecords );
        if( count( $dealerRecords ) ){
            echo "<h3>Dealer Rater Setups</h3>";
            echo $this->buildTable( $dealerRecords );
        }
        echo "</div>";

	}

	private function buildTable( $data ){
        $dataIntoTable = "
			<table class='table table-inverse'>
			<tr>
				<th> Title </th><th> URL </th> <th> Status </th> <th> Actions </th>
			</tr>
		";
        foreach ( $data as $value) {
            $status = ( $value->active == null || $value->active == false ) ? 'Active' : 'Deactive';
            $statusClassName = ( $value->active == null || $value->active == false ) ? 'btn-info' : 'btn-danger';
            $dataIntoTable .= "
			<tr>
				<td> ". $value->title ." </td> <td> ". $value->api_url ." </td> 
				<td> 
					<form method='post' action='".$_SERVER['REQUEST_URI']."'>
						<input type='hidden' value='". $value->ID ."' name='detail_id' >
						<input type='hidden' value='". $value->app_name ."' name='app_name' >
						<input type='submit' value='". $status ."' class='btn btn-sm $statusClassName' name='update_status_detail'>
					</form> 
				 </td>
				<td>
					<form method='post' action='".$_SERVER['REQUEST_URI']."'>
						<input type='hidden' value='". $value->ID ."' name='detail_id' >
						<input type='submit' value='Delete' class='btn btn-sm btn-danger' name='detail_imk_detail'>
					</form> </td>
			</tr>
		";
        }
        $dataIntoTable .= "</table>";
        return $dataIntoTable;
    }

	/*
		@return int
		1: success
	*/
	public function deleteDetail( $data ){
		$deleted = $this->db->query("DELETE FROM ". $this->tablename ." WHERE ID = " . $data['detail_id']);

	}

	public function updateDetailStatus( $data ){

		$this->updateDetail( ['active'=> 0, 'app_name' => $data['app_name'] ] );
		$this->updateDetail( $data );
	}

	public function updateDetail( $data ){

		$condition = "WHERE app_name='". $data['app_name'] ."' ";
		if(isset( $data['detail_id'] ) && !empty( $data['detail_id'] ) ){
			$condition .= " and ID = ". $data['detail_id'];
		}
		
		$fields = [];
		if( isset( $data['detail_title'] ) ){
			$fields['detail_title'] = $data['detail_title'];
		}

		if( isset( $data['active'] ) ){
			$fields['active'] = $data['active'];
		}

		if( isset( $data['user_id'] ) ){
			$fields['user_id'] = $data['user_id'];
		}

		if( isset( $data['group_id'] ) ){
			$fields['group_id'] = $data['group_id'];
		}


		if( isset( $data['api_url'] ) ){
			$fields['api_url'] = $data['api_url'];
		}


		if( isset( $data['api_key'] ) ){
			$fields['api_key'] = $data['api_key'];
		}

		$fieldsStrArray = [];
		foreach ( $fields as $key => $value) {
			array_push($fieldsStrArray, $key ."=". $value );
		}

		$fieldsStr = implode(", ", $fieldsStrArray);

		$sqlStr = "UPDATE ". $this->tablename ." SET $fieldsStr $condition";
		$deleted = $this->db->query( $sqlStr );

	}
}