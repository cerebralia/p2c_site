<?php

define('IN_PAGE', TRUE);

require_once "engine/classes/class.Mysql.php";
require_once "engine/classes/class.Users.php";

require_once "engine.php";

CEngineDB::Init();

$g_Users = new CUsers();

$hack_name = GetSettingsValueByName("cheat_name");

$is_hwid = isset($_GET['update']);

if ($is_hwid)
{
	$user_hwid = base64_decode($_GET['update']);
	
	if ( $user_hwid && ValidUserHWID($user_hwid) && UserLicenseValid($user_hwid) )
	{
		$all_build_count = GetBuildCount();
		
		if ( $all_build_count > 0 )
		{
			$user_buidl_url = "?update=".base64_encode($user_hwid)."&download=ok";
			
			if ( IsUserDownloadBuild($user_hwid) )
			{
				$page_info = "[".$hack_name."] You have already updated the cheat.";
			}
			else
			{
				$page_info = "[".$hack_name."] Click the download button";
				
				$check_download =( isset($_GET['download'] ) ? $_GET['download'] : "");
				
				if ( $check_download == "ok" )
				{
					DownloadNewBuild();
					AddUserDownloadList($user_hwid);
				}
			}
		}
		else
		{
			$page_info = "[".$hack_name."] There are no more versions.";
		}
	}
	else
	{
		header("Location: http://google.com/");
	}
}
else
{
	header("Location: http://google.com/");
}

function DownloadNewBuild()
{
	$update_dir = BUILD_PATH;
	
	$open_dir_build = opendir($update_dir);
	
	$array_exe_files = array();
	
	while( $file = readdir($open_dir_build) )
	{
		if( $file == '.' || $file == '..' || is_dir($update_dir.$file) )
			continue;

		$file_info = new SplFileInfo($file);
		
		if ( $file_info->getExtension() == "exe" )
		{
			array_push($array_exe_files,$file);
		}
	}
	
	closedir($open_dir_build);
	
	$rand_build_id = rand(0,count($array_exe_files) - 1);
	
	$exe_file= new SplFileInfo($update_dir.$array_exe_files[$rand_build_id]);

	if( extension_loaded('zip') )
	{
		$zip = new ZipArchive();
		$zip_name = date("d.m.y")."-".time().".zip";
		
		if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
			exit;
		
		$zip->addFile($exe_file);

		$zip->close();
		
		if( file_exists($zip_name) )
		{
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"".$zip_name."\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($zip_name));
			readfile($zip_name);
			unlink($zip_name);
			unlink($exe_file);
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title><?php echo $hack_name; ?> Update [ recode by memes4u1337 ]</title>
	<style type="text/css">
	body
	{
		background: #363d47;
		color: white;
		font: normal 12px 'Open Sans', sans-serif;
		margin-top: 20px;
	}

	h1
	{
		display: block;
		font-size: 2em;
		-webkit-margin-before: 0.67em;
		-webkit-margin-after: 0.67em;
		-webkit-margin-start: 0px;
		-webkit-margin-end: 0px;
		font-weight: bold;
	}

	ul.countdown
	{
		list-style: none;
		margin: 75px 0;
		padding: 0;
		display: block;
		text-align: center;
	}

	ul.countdown li
	{
		display: inline-block;
	}

	ul.countdown li span
	{
		font-size: 80px;
		font-weight: 300;
		line-height: 80px;
	}

	ul.countdown li p
	{
		color: #a7abb1;
		font-size: 14px;
	}

	p
	{
		display: block;
		-webkit-margin-before: 1em;
		-webkit-margin-after: 1em;
		-webkit-margin-start: 0px;
		-webkit-margin-end: 0px;
	}
	
	.btn 
	{
		border-width: 2px;
		text-decoration: none;
	}
	
	.btn-primary:hover
	{
		color: #ffffff;
		background-color: #28415b;
		border-color: #253c54;
	}
	.btn-primary:active,
	.btn-primary.active,
	.open > .dropdown-toggle.btn-primary {
	  color: #ffffff;
	  background-color: #28415b;
	  border-color: #253c54;
	}
	.btn-primary:active:hover,
	.btn-primary.active:hover,
	.open > .dropdown-toggle.btn-primary:hover,
	.btn-primary:active:focus,
	.btn-primary.active:focus,
	.open > .dropdown-toggle.btn-primary:focus,
	.btn-primary:active.focus,
	.btn-primary.active.focus,
	.open > .dropdown-toggle.btn-primary.focus {
	  color: #ffffff;
	  background-color: #1d2f43;
	  border-color: #101b26;
	}
	.btn-primary:active,
	.btn-primary.active,
	.open > .dropdown-toggle.btn-primary {
	  background-image: none;
	}
	.btn-primary.disabled:hover,
	.btn-primary[disabled]:hover,
	fieldset[disabled] .btn-primary:hover,
	.btn-primary.disabled:focus,
	.btn-primary[disabled]:focus,
	fieldset[disabled] .btn-primary:focus,
	.btn-primary.disabled.focus,
	.btn-primary[disabled].focus,
	fieldset[disabled] .btn-primary.focus {
	  background-color: #375a7f;
	  border-color: #375a7f;
	}
	.btn-primary .badge {
	  color: #375a7f;
	  background-color: #ffffff;
	}
	.btn-primary
	{
		color: #ffffff;
		background-color: #375a7f;
		border-color: #375a7f;
	}
	.btn
	{
		display: inline-block;
		margin-bottom: 0;
		font-weight: normal;
		text-align: center;
		vertical-align: middle;
		-ms-touch-action: manipulation;
		touch-action: manipulation;
		cursor: pointer;
		background-image: none;
		border: 1px solid transparent;
		white-space: nowrap;
		padding: 10px 15px;
		font-size: 15px;
		line-height: 1.42857143;
		border-radius: 4px;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
	</style>
</head>

<body>
	
	<h1 align="center" style="margin-top:150px;"><?php echo $page_info; ?></h1>
	
	<ul class="countdown">
	<li>
		<span class="days"><?php echo $all_build_count; ?></span>
		<p class="days_ref">Total unique versions</p>
		<a href="<?php echo $user_buidl_url; ?>" class="btn btn-primary btn-sm">download</a>
	</li>
</ul>
	
</body>

</html>