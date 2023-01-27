<?php

/*
  This is a ***DEMO*** , the backend / PHP provided is very basic. You can use it as a starting point maybe, but ***do not use this on production***. It doesn't preform any server-side validation, checks, authentication, etc.

  For more read the README.md file on this folder.

  Based on the examples provided on:
  - http://php.net/manual/en/features.file-upload.php
*/

header('Content-type:application/json;charset=utf-8');

try {
    if (
        !isset($_FILES['file']['error']) ||
        is_array($_FILES['file']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }
	// get wordpress files path
	
	$path=wp_upload_dir();  //print_r($path); die;
	$filebaseurl=$path['baseurl'];
	$fileuploadurl=$path['basedir'];
	
	$filename=date('dmyhis').basename($_FILES['picture']['name']);
	$uploadpath=$fileuploadurl."/".$filename;
	$uploadurl_filename=$fileuploadurl."/".$filename;
	if(move_uploaded_file($_FILES['picture']['tmp_name'],$uploadurl_filename))
	{
		$picture=$filebaseurl.'/'.$filename;
		//$picture=$filename;
		$sql="INSERT INTO   `$tblimg`  ( `sliderid` ,`imagepath`,`created`,`createdby`,`status`) 
                    VALUES ('".$_REQUEST['sliderid']."','".$picture."','".date('Y-m-d H:i:s')."','0','Active')";	
		$wpdb->query($sql);
	
	}else{
        throw new RuntimeException('Failed to move uploaded file.');
    }
	
	/*
    $filepath = sprintf('files/%s_%s', uniqid(), $_FILES['file']['name']);

    if (!move_uploaded_file(
        $_FILES['file']['tmp_name'],
        $filepath
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
	*/

    // All good, send the response
    echo json_encode([
        'status' => 'ok',
        'path' => $filepath
    ]);

} catch (RuntimeException $e) {
	// Something went wrong, send the err message as JSON
	http_response_code(400);

	echo json_encode([
		'status' => 'error',
		'message' => $e->getMessage()
	]);
}