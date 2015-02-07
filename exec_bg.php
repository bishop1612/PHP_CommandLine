<?php
require_once "/home/www/ssl/oiscweb/lib/oiscSession.php";

function exec_bg ($scriptPath, $args = false, $saveEnv = true){
	// Make sure path is full path
	$scriptPath = realpath($scriptPath);
	
	// Save current environment
	$env = array();
	$env["SAVE"] = $saveEnv;
	
	if($saveEnv){
		$env["_GET"]		= $_GET;
		$env["_REQUEST"]	= $_REQUEST;
		$env["_POST"]		= $_POST;
		$env["_SERVER"]		= $_SERVER;
		$env["_COOKIE"]		= $_COOKIE;
	}
	$env["_SESSION"]	= class_exists("oiscSession", false) ? @$_SESSION : false;
	
	// Save book keeping data
	$env["exec_bg"] = array(
		"script"	=> $scriptPath,
		"args"		=> $args
	);
	
	// Create a job id and file
	$jobId = md5(uniqid(time()));
	$jobFilePath = "/home/www/ssl/oiscweb/files/temp/exec_bg." . $jobId . ".job";
	$jobFileHandle = fopen($jobFilePath, 'w') or die("can't open file");

	// Write $env to jobFile
	fwrite($jobFileHandle, serialize($env));
	fclose($jobFileHandle);
	
	usleep(500000); // half second
	
	// Exec bg_worker.php, passing in $jobId
	return exec("echo \"" . $jobId . "\" | php /home/www/ssl/oiscweb/lib/exec_bg/bg_worker.php > /home/www/ssl/oiscweb/files/temp/exec_bg." . $jobId . ".log &");	
	
}


?>