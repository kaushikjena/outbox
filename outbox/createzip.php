<?php

// Adding files to a .zip file, no zip file exists it creates a new ZIP file

// increase script timeout value
ini_set('max_execution_time', 5000);
$cdate =date("Y-m-d");
// create object
$zip = new ZipArchive();

// open archive 
if ($zip->open('my-archive.zip', ZIPARCHIVE::CREATE) !== TRUE) {
    die ("Could not open archive");
}

// initialize an iterator
// pass it the directory to be processed
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("uploadsback/"));

// iterate over the directory
// add each file found to the archive
foreach ($iterator as $key=>$value) {
	echo $key."<br/>";
	echo filemtime($key);
	echo "<br />";
	echo "Last modified: ".date("F d Y H:i:s.",filemtime($key));
	echo "<br />";
	if(date("Y-m-d",filemtime($key)) < $cdate){
   		$zip->addFile(realpath($key), $key) or die ("ERROR: Could not add file: $key");
		//unlink($key);
	}
	
}

// close and save archive
$zip->close();
echo "Archive created successfully.";
?>