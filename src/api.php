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
//            Check 'did' exist on not              //
//                                                  //
//////////////////////////////////////////////////////

function didExists($did){
    $qry = "SELECT did FROM driver WHERE did=$did";	
    $result = mysql_query($qry);
    return mysql_num_rows($result) >= 1;
}

//////////////////////////////////////////////////////
//                                                  //
//            Check 'admin_id' exist on not         //
//                                                  //
//////////////////////////////////////////////////////

function adminIdExists($admin_id){
    $qry = "SELECT id FROM admin WHERE id=$admin_id";	
    $result = mysql_query($qry);
    return mysql_num_rows($result) >= 1;
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

    $ret = array('uid' => '0', 'email' => '0', 'op' => 'admin_login', 'msg'=> 'Login Successful', 'error_code'=> '0');

    // reading posted params
    $username = $_POST['username'];
    //$pass     = $_POST['password'];
    //$password = md5($pass . $username);
    $password   = $_POST['password'];

    $qry = "SELECT * FROM admin WHERE username='$username' AND password='$password'";	
    $result = mysql_query($qry);

    $row = mysql_fetch_assoc($result);
    $aid = $row['id'];
    $email = $row['email'];

    if($result) {
        if(mysql_num_rows($result) > 0) {	
            $login_ok = true;
        }
    }

    if($login_ok){
        $ret['aid'] = $aid;
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
//       Api for driver login                       //
//                                                  //
//////////////////////////////////////////////////////
function handleDriverLogin(){

    $ret = array('uid' => '0', 'email' => '0', 'op' => 'driver_login', 'msg'=> 'Login Successful', 'error_code'=> '0');

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
//       Api for add driver location                //
//                                                  //
//////////////////////////////////////////////////////
function handleAddDriverLocation(){

    $ret = array('op' => 'add_driver_location', 'msg'=> 'Location added successfully', 'error_code'=> '0');

    $did = $_POST['did'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $ts  = $_POST['ts'];

    // ensuring that 'did' is valid
    if(!didExists($did)) {
        $ret["error_code"] = 2;
        $ret["msg"] = "Driver id does not exist";
        echo json_encode($ret);
        return;
    }

    // writing driver's location in db
    $query = "INSERT INTO location(`did`,`lat`,`lng`,`ts`) VALUES($did, $lat, $lng,$ts)";
    $result = mysql_query($query);

    if ($result > 0) {}
    else {
        $ret["error_code"] = 1;
        $ret["msg"] = "Failed to add driver locaiton";
    }

    // updating drivers last location and timestamp
    mysql_query("update driver set last_loc=$lat,last_lng=$lng,last_ts=$ts where id=$did)";

    echo json_encode($ret);
}

function getDriversForAdmin($admin_id) {
    $ret = array('op' => 'get_drivers_ovreview', 'msg' => 'Got Drivers Overview Successfully', 'error_code' => '0');

    # ensuring admin id
    if(!adminIdExists($admin_id)) {
        $ret["error_code"] = 1;
        $ret["msg"] = "Invalid admin id";
        return json_encode($ret);
    }

    # fetching drivers' overview data
    $result = mysql_query("select * from driver where admin_id=$admin_id");

    $drivers = array();
    while( $row = mysql_fetch_assoc($result) ) {
        $drivers[] = $row;
    }
    $ret["drivers"] = $drivers;
    return json_encode($ret);
}

function handleGetDriversOverview() {
    echo getDriversForAdmin($_POST["admin_id"]);
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
if($op == "admin_login")            handleAdminLogin();
if($op == "driver_login")           handleDriverLogin();
if($op == "add_driver_location")    handleAddDriverLocation();
if($op == "get_drivers_ovreview")   handleGetDriversOverview();

//-------------------------------------
// UNUSED APIs
//-------------------------------------
if($op == "register")      handleRegister();
if($op == "add_plate")     handleAddLicensePlate();
if($op == "get_max_plate") handleGetMaxPlate();

?>
