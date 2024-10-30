<?php
/*
  * @package IMKServicePlugin
  * */
echo "<div class='container-fluid'><h1 class='imk-admin-header' > Integrated Apps </h1></div>";

if( isset( $_POST['detail_imk_detail'] ) ){
	$this->deleteDetail( $_POST );
}

if( isset( $_POST['update_status_detail'] ) ){
	if( $_POST['update_status_detail'] == 'Active' ){
		$_POST['active'] = 1;
	} else {
		$_POST['active'] = 0;
	}
	$this->updateDetailStatus( $_POST );
}


$this-> get_user_detail();