<?php

// database.config.php" file connects to database every time
include("database.config.php");	

//////////////////////////////////////////////////////
//                                                  //
//            Check user exist on not               //
//                                                  //
//////////////////////////////////////////////////////

function userExists($username){
	$qry = "SELECT username FROM user WHERE username='$username'";	
	$result = mysql_query($qry);
	
	return mysql_num_rows($result) >= 1;
}

//////////////////////////////////////////////////////
//                                                  //
//            Check if license plate exists         //
//                                                  //
//////////////////////////////////////////////////////

function licensePlateExists($license_plate_num){
	$qry = "SELECT license_plate_num FROM license_plate WHERE license_plate_num='$license_plate_num'";	
	$result = mysql_query($qry);
	
	return mysql_num_rows($result) >= 1;
}


//////////////////////////////////////////////////////
//                                                  //
// function to add license plate information in db  //
//                                                  //
//////////////////////////////////////////////////////
function AddLicensePlateInfo($user_id, $license_plate_num, $license_plate_state, $advertised_location, $make, $model, $color, $year, $vin, $needs) {

  // upload the license plate image in data folder

  $ret = array('error' => 0, 'msg' => '');

  // ensure that license plate does not exist
  if(licensePlateExists($license_plate_num) ){	
		$ret["error_code"] = 1;
       	$ret["msg"]        = "License plate already exists";	
        die( json_encode($ret) );

  }

  // timestamp !
  $t = time();

  // getting file name, type and extension
  $tmpFilePath = $_FILES['license_file']['name'];
  
  // getting file extension
  $ext = end( (explode(".", $tmpFilePath)) );
  $file_name = $user_id . "_" . $t . "." . $ext;	
  
  // destination path
  $target_file = "data/".  $file_name;

  // uploading file into data folder
  $res = move_uploaded_file($_FILES['license_file']['tmp_name'], $target_file);

  // making query
  $query = "INSERT INTO license_plate";
  $query = $query . "(`user_id`,`license_plate_num`,`license_plate_state`,`advertised_location`,`make`,`model`,`color`,`year`,`vin`,`needs`,`license_file`)";
  $query = $query . " VALUES('$user_id', '$license_plate_num','$license_plate_state','$advertised_location','$make','$model','$color','$year', '$vin', '$needs', '$file_name')";

  // echo $query . "<br>";

  // executing query
  $result = mysql_query($query);
			
  if ($result > 0) {
	 $ret["msg"] = "License plate added successfully";	
	 echo json_encode($ret);
     return mysql_insert_id();
  }

  return 0;						 	 
}

//////////////////////////////////////////////////////
//                                                  //
//     SYNC function to get all license plates      //
//     upto synced index in app                     //
//                                                  //
//////////////////////////////////////////////////////
function getAllLicensePlates($index) {


	$query = "select * from license_plate where id > $index limit 20";

	//echo $query . "<br>";

	$result = mysql_query($query);
	
    $plates = array();
    while( $row = mysql_fetch_assoc($result) ) {
        $plates[] = $row;
    }

    return json_encode($plates);
}




//////////////////////////////////////////////////////
//                                                  //
//     Api for new user registration                //
//                                                  //
//////////////////////////////////////////////////////

function handleRegister(){

	$ret = array('op' => 'register', 'msg' => 'Registration Successful', 'error_code' => '0');

	$username = $_POST['username'];
	$pass     = $_POST['password'];
	$email    = $_POST['email'];

	$password = md5($pass . $username);

	// ensuring username and password are specified in request
	if (empty($_POST['username']) || empty($_POST['password'])){

		// creating some data that will be the JSON response
        	$ret["error_code"] = 1;
       		$ret["msg"]        = "Please Enter Both Username and Password.";
	        
		die( json_encode($ret) );
	}
		
	// ensure that username does not exist 
	if( userExists($username) ){
    		$ret["error_code"] = 1;
       		$ret["msg"]        = "Username already exists";	
                die( json_encode($ret) );
   	}
	
	$result = mysql_query("INSERT INTO user(`username`,`password`,`email`) VALUES('$username', '$password', '$email')");
			
	if ($result > 0) {																									
	// creating some data that will be the JSON response
        echo json_encode($ret);
	} else {

		// some unknow error, fix me later !!!!
	}
}// handleRegister


//////////////////////////////////////////////////////
//                                                  //
//       Api for admin login                         //
//                                                  //
//////////////////////////////////////////////////////
function handleAdminLogin(){

	$ret = array('uid' => '0', 'email' => '0', 'op' => 'login', 'msg'=> 'Login Successful', 'error_code'=> '0');

    // reading posted params
	$username = $_POST['username'];
	//$pass     = $_POST['password'];
	//$password = md5($pass . $username);
	$password   = $_POST['password'];

	$qry = "SELECT * FROM admin WHERE username='$username' AND password='$password'";	
	$result = mysql_query($qry);
	
	$row = mysql_fetch_assoc($result);
	$uid = $row['id'];
	$email = $row['email'];

	if($result) {
		if(mysql_num_rows($result) > 0) {	
			$login_ok = true;
		}
	}

	if($login_ok){
		$ret['uid'] = $uid;
		$ret['email'] = $email;
        echo json_encode($ret);
	}else{
	    $ret["error_code"] = 1;
	    $ret["msg"]        = "Invalid Credentials!";
	    
	    die( json_encode($ret) );
	}
}

//////////////////////////////////////////////////////
//                                                  //
//       Api for driver login                         //
//                                                  //
//////////////////////////////////////////////////////
function handleDriverLogin(){

	$ret = array('uid' => '0', 'email' => '0', 'op' => 'login', 'msg'=> 'Login Successful', 'error_code'=> '0');

    // reading posted params
	$username = $_POST['username'];
	$password = $_POST['password'];
	$phone_no = $_POST['phone_no'];

	$qry = "SELECT * FROM driver WHERE username='$username' AND password='$password' AND phone_no='$phone_no'";	
	$result = mysql_query($qry);
	
	$row = mysql_fetch_assoc($result);
	$did = $row['id'];
	$email = $row['email'];

	if($result) {
		if(mysql_num_rows($result) > 0) {	
			$login_ok = true;
		}
	}

	if($login_ok){
		$ret['did'] = $did;
		$ret['email'] = $email;
        echo json_encode($ret);
	}else{
	    $ret["error_code"] = 1;
	    $ret["msg"]        = "Invalid Credentials!";
	    
	    die( json_encode($ret) );
	}
}

//////////////////////////////////////////////////////
//                                                  //
//       Api for add plate                          //
//                                                  //
//////////////////////////////////////////////////////

function handleAddLicensePlate(){
	$user_id             = $_POST['user_id'];
	$license_plate_num   = $_POST['license_plate_num'];
	$license_plate_state = $_POST['license_plate_state'];
	$advertised_location = $_POST['advertised_location'];
	$make 				 = $_POST['make'];
	$model 				 = $_POST['model'];
	$color 				 = $_POST['color'];

	//---------------------------
	// checking optional fields
	//---------------------------
	$year = 0;
	if(isset($_POST['year'])) {
		if(isset($_POST['year']) == "")  $year = 0;
		else                             $year = $_POST['year'];
	}

	$vin = "Unknown";
	if(isset($_POST['vin'])) {
		$vin = $_POST['vin'];
	}

	$needs = "None";
	if(isset($_POST['needs'])) {
		$needs = $_POST['needs'];
	}

	// saving license plate information
    AddLicensePlateInfo($user_id, $license_plate_num, $license_plate_state, $advertised_location, $make, $model, $color, $year, $vin, $needs);
}

function handleGetPlates() {
	echo getAllLicensePlates($_POST['index']);
}

function handleGetMaxPlate() {
	$ret = array("max_id" => 0);
	$result = mysql_query("SELECT MAX(id) FROM license_plate");
    $row = mysql_fetch_row($result);
    $ret["max_id"] = $row[0];
    echo json_encode($ret);
}

///////////////////////////////////////////////////////////////////////
//                                                                   //
//                         MAIN                                      //
//                                                                   // 
///////////////////////////////////////////////////////////////////////

if(!isset($_POST["op"]))  die("operation not specified");

// reading api operation
$op = $_POST["op"];

// api request handlers for various operations
if($op == "admin_login")   handleAdminLogin();
if($op == "driver_login")  handleDriverLogin();
if($op == "register")      handleRegister();
if($op == "add_plate")     handleAddLicensePlate();
if($op == "get_plates")    handleGetPlates();
if($op == "get_max_plate") handleGetMaxPlate();
?>
