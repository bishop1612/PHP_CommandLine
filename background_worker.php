<?php

// if console
if( php_sapi_name() == 'cli' ){
	// Get jobId from stdin
	$jobId = trim(fgets(STDIN));
	
	// Log
	echo "################# exec_bg() bg_worker.php (BEGIN) ################# \n";
	echo "\$jobId = $jobId;  // ". date('r'). "\n";

	// Open jobFile for reading
	$jobFilePath = "/home/www/ssl/oiscweb/files/temp/exec_bg." . $jobId . ".job";
	$jobFileHandle = fopen($jobFilePath, "r") or die("can't open file {$jobFilePath}");;

	// Read jobFile into $env
	$env = unserialize(fread($jobFileHandle, filesize($jobFilePath)));
	fclose($jobFileHandle);
	
	// Log $env
	echo "\n\n\n############## exec_bg() bg_worker.php (ENVIRONMENT) ############## \n";
	print_r($env);
	
	// Delete jobFile
	//unlink($jobFilePath);
	
	// Delete exec_bg files older than 60 min
	exec("find /home/www/ssl/oiscweb/files/temp/exec_bg.* -mmin +60 -exec rm {} \;");
	
	// Restore environment
	if($env["SAVE"]){
		$_GET		= $env["_GET"];
		$_REQUEST	= $env["_REQUEST"];
		$_POST		= $env["_POST"];
		$_SERVER	= $env["_SERVER"];
		$_COOKIE	= $env["_COOKIE"];
	}
	$OISC_EXEC_BG_SESSION	= $env["_SESSION"]; // oiscSession handles $_SERVER restore
	
	// Export API for exec_bg args
	$OISC_EXEC_BG = $env["exec_bg"]["args"];
	
	// Minimize our presence 
	$OISC_EXEC_BG_WORKER_CHILD_SCRIPT_PATH = $env["exec_bg"]["script"];
	unset($jobId);
	unset($jobFilePath);
	unset($jobFileHandle);
	unset($env);
	
	// Log
	echo "\n\n\n############### exec_bg() bg_worker.php (RUN SCRIPT) ############## \n";
	echo $OISC_EXEC_BG_WORKER_CHILD_SCRIPT_PATH ."\n\n";
	
	// Run the script
	include_once($OISC_EXEC_BG_WORKER_CHILD_SCRIPT_PATH);
	
	echo "\n\n\n############### exec_bg() bg_worker.php (SCRIPT DONE) ############## \n";
	
	if(class_exists("oiscSession", false)){
		// Log console log
		echo "\n\n\n########## exec_bg() bg_worker.php (SESSION CONSOLE LOG) ########## \n";
		print_r(oiscSession::consoleGet());
	}

} else {
	$OISC_EXEC_BG = false;
	$OISC_EXEC_BG_SESSION = false;
}

?>