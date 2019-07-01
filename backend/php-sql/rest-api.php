<?php

/**
 * @author Cordotest <kejarkoding@gmail.com>
 * @copyright Cordotest 2019
 * @package cordotest
 * 
 * 
 * Created using Ionic App Builder
 * http://codecanyon.net/item/ionic-mobile-app-builder/15716727
 */


/** CONFIG:START **/
$config["host"] 		= "localhost" ; 		//host
$config["user"] 		= "root" ; 		//Username SQL
$config["pass"] 		= "" ; 		//Password SQL
$config["dbase"] 		= "db_cordotest" ; 		//Database
$config["utf8"] 		= true ; 		//turkish charset set false
$config["timezone"] 		= "Asia/Jakarta" ; 		// check this site: http://php.net/manual/en/timezones.php
$config["abs_url_images"] 		= "http://domain.com/apps/cordotest//media/image/" ; 		//Absolute Images URL
$config["abs_url_videos"] 		= "http://domain.com/apps/cordotest//media/media/" ; 		//Absolute Videos URL
$config["abs_url_audios"] 		= "http://domain.com/apps/cordotest//media/media/" ; 		//Absolute Audio URL
$config["abs_url_files"] 		= "http://domain.com/apps/cordotest//media/file/" ; 		//Absolute Files URL
$config["image_allowed"][] 		= array("mimetype"=>"image/jpeg","ext"=>"jpg") ; 		//whitelist image
$config["image_allowed"][] 		= array("mimetype"=>"image/jpg","ext"=>"jpg") ; 		
$config["image_allowed"][] 		= array("mimetype"=>"image/png","ext"=>"png") ; 		
$config["file_allowed"][] 		= array("mimetype"=>"text/plain","ext"=>"txt") ; 		
$config["file_allowed"][] 		= array("mimetype"=>"","ext"=>"tmp") ; 		
/** CONFIG:END **/

date_default_timezone_set($config['timezone']);
if(isset($_SERVER["HTTP_X_AUTHORIZATION"])){
	list($_SERVER["PHP_AUTH_USER"],$_SERVER["PHP_AUTH_PW"]) = explode(":" , base64_decode(substr($_SERVER["HTTP_X_AUTHORIZATION"],6)));
}
$rest_api=array("data"=>array("status"=>404,"title"=>"Not found"),"title"=>"Error","message"=>"Routes not found");

/** connect to mysql **/
$mysql = new mysqli($config["host"], $config["user"], $config["pass"], $config["dbase"]);
if (mysqli_connect_errno()){
	die(mysqli_connect_error());
}


if(!isset($_GET["json"])){
	$_GET["json"]= "route";
}
if((!isset($_GET["form"])) && ($_GET["json"] == "submit")) {
	$_GET["json"]= "route";
}

if($config["utf8"]==true){
	$mysql->set_charset("utf8");
}

$get_dir = explode("/", $_SERVER["PHP_SELF"]);
unset($get_dir[count($get_dir)-1]);
$main_url = "http://" . $_SERVER["HTTP_HOST"] . implode("/",$get_dir)."/";


switch($_GET["json"]){	
	// TODO: -+- Listing : biografi
	case "biografi":
		$rest_api=array();
		$where = $_where = null;
		// TODO: -+----+- statement where
		if(isset($_GET["provinsi"])){
			if($_GET["provinsi"]!="-1"){
				$_where[] = "`provinsi` LIKE '".$mysql->escape_string($_GET["provinsi"])."'";
			}
		}
		if(isset($_GET["nama"])){
			if($_GET["nama"]!="-1"){
				$_where[] = "`nama` LIKE '".$mysql->escape_string($_GET["nama"])."'";
			}
		}
		if(isset($_GET["id"])){
			if($_GET["id"]!="-1"){
				$_where[] = "`id` = '".$mysql->escape_string($_GET["id"])."'";
			}
		}
		if(is_array($_where)){
			$where = " WHERE " . implode(" AND ",$_where);
		}
		// TODO: -+----+- orderby
		$order_by = "`id`";
		$sort_by = "DESC";
		if(!isset($_GET["order"])){
			$_GET["order"] = "`id`";
		}
		// TODO: -+----+- sort asc/desc
		if(!isset($_GET["sort"])){
			$_GET["sort"] = "desc";
		}
		if($_GET["sort"]=="asc"){
			$sort_by = "ASC";
		}else{
			$sort_by = "DESC";
		}
		if($_GET["order"]=="id"){
			$order_by = "`id`";
		}
		if($_GET["order"]=="provinsi"){
			$order_by = "`provinsi`";
		}
		if($_GET["order"]=="nama"){
			$order_by = "`nama`";
		}
		if($_GET["order"]=="random"){
			$order_by = "RAND()";
		}
		$limit = 100;
		if(isset($_GET["limit"])){
			$limit = (int)$_GET["limit"] ;
		}
		// TODO: -+----+- SQL Query
		$sql = "SELECT * FROM `biografi` ".$where."ORDER BY ".$order_by." ".$sort_by." LIMIT 0, ".$limit." " ;
		if($result = $mysql->query($sql)){
			$z=0;
			while ($data = $result->fetch_array()){
				if(isset($data['id'])){$rest_api[$z]['id'] = $data['id'];}; # id
				if(isset($data['provinsi'])){$rest_api[$z]['provinsi'] = $data['provinsi'];}; # text
				if(isset($data['nama'])){$rest_api[$z]['nama'] = $data['nama'];}; # text
				$z++;
			}
			$result->close();
			if(isset($_GET["id"])){
				if(isset($rest_api[0])){
					$rest_api = $rest_api[0];
				}else{
					$rest_api=array("data"=>array("status"=>404,"title"=>"Not found"),"title"=>"Error","message"=>"Invalid ID");
				}
			}
		}

		break;
	// TODO: -+- route
	case "route":		$rest_api=array();
		$rest_api["site"]["name"] = "Cordotest" ;
		$rest_api["site"]["description"] = "Cordotest" ;
		$rest_api["site"]["imabuilder"] = "rev18.12.10" ;

		$rest_api["routes"][0]["namespace"] = "biografi";
		$rest_api["routes"][0]["tb_version"] = "";
		$rest_api["routes"][0]["methods"][] = "GET";
		$rest_api["routes"][0]["args"]["id"] = array("required"=>"false","description"=>"Selecting `biografi` based `id`");
		$rest_api["routes"][0]["args"]["provinsi"] = array("required"=>"false","description"=>"Selecting `biografi` based `provinsi`");
		$rest_api["routes"][0]["args"]["nama"] = array("required"=>"false","description"=>"Selecting `biografi` based `nama`");
		$rest_api["routes"][0]["args"]["order"] = array("required"=>"false","description"=>"order by `random`, `id`, `provinsi`, `nama`");
		$rest_api["routes"][0]["args"]["sort"] = array("required"=>"false","description"=>"sort by `asc` or `desc`");
		$rest_api["routes"][0]["args"]["limit"] = array("required"=>"false","description"=> "limit the items that appear","type"=>"number");
		$rest_api["routes"][0]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=biografi";
		$rest_api["routes"][1]["namespace"] = "submit/biografi";
		$rest_api["routes"][1]["tb_version"] = "";
		$rest_api["routes"][1]["methods"][] = "POST";
		$rest_api["routes"][1]["_links"]["self"] = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]."?json=submit&form=biografi";
		$rest_api["routes"][1]["args"]["provinsi"] = array("required"=>"true","description"=>"Insert data to field `provinsi` in table `biografi`");
		$rest_api["routes"][1]["args"]["nama"] = array("required"=>"true","description"=>"Insert data to field `nama` in table `biografi`");
		break;
	// TODO: -+- submit

	case "submit":
		$rest_api=array();

		$rest_api["methods"][0] = "POST";
		$rest_api["methods"][1] = "GET";
		switch($_GET["form"]){
			// TODO: -+----+- biografi
			case "biografi":


				$rest_api["auth"]["basic"] = false;

				$rest_api["args"]["provinsi"] = array("required"=>"true","description"=>"Receiving data from the input `provinsi`");
				$rest_api["args"]["nama"] = array("required"=>"true","description"=>"Receiving data from the input `nama`");
				if(!isset($_POST["provinsi"])){
					$_POST["provinsi"]="";
				}
				if(!isset($_POST["nama"])){
					$_POST["nama"]="";
				}
				$rest_api["message"] = "Please! complete the form provided.";
				$rest_api["title"] = "Notice!";
				if(($_POST["provinsi"] != "") || ($_POST["nama"] != "")){
					// avoid undefined
					$input["provinsi"] = "";
					$input["nama"] = "";
					// variable post
					if(isset($_POST["provinsi"])){
						$input["provinsi"] = $mysql->escape_string($_POST["provinsi"]);
					}

					if(isset($_POST["nama"])){
						$input["nama"] = $mysql->escape_string($_POST["nama"]);
					}

					$sql_query = "INSERT INTO `biografi` (`provinsi`,`nama`) VALUES ('".$input["provinsi"]."','".$input["nama"]."' )";
					if($query = $mysql->query($sql_query)){
						$rest_api["message"] = "Your request has been sent.";
						$rest_api["title"] = "Successfully";
					}else{
						$rest_api["message"] = "Form input and SQL Column do not match.";
						$rest_api["title"] = "Fatal Error!";
					}
				}else{
					$rest_api["message"] = "Please! complete the form provided.";
					$rest_api["title"] = "Notice!";
				}

				break;

		}


	break;

}


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization,X-Authorization');
if (!isset($_GET["callback"])){
	header('Content-type: application/json');
	if(defined("JSON_UNESCAPED_UNICODE")){
		echo json_encode($rest_api,JSON_UNESCAPED_UNICODE);
	}else{
		echo json_encode($rest_api);
	}

}else{
	if(defined("JSON_UNESCAPED_UNICODE")){
		echo strip_tags($_GET["callback"]) ."(". json_encode($rest_api,JSON_UNESCAPED_UNICODE). ");" ;
	}else{
		echo strip_tags($_GET["callback"]) ."(". json_encode($rest_api) . ");" ;
	}

}