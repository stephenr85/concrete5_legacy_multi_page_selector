<?php

//Set the content type
header("Content-type:text/css");

if(!isset($file) && isset($_REQUEST['file'])){
	$file = $_REQUEST['file'];
}

if(strpos($file, '/') === 0) $file = BASE_URL.$file;

//Make sure it's a local file
echo '/*'. $file .'*/';

if(file_exists($file) || strpos($file, BASE_URL) === 0){

	//Get the stylesheet contents
	$fh = Loader::helper('file');
	$original = $fh->getContents($file);
	
	if(is_string($original) && strlen($original) > 0){
		//Replace paths
		$final = preg_replace("/url\(\/concrete\//i", 'url('.DIR_REL.'/'.DIRNAME_APP.'/', $original);
		
		
		//Send the output	
		echo $final;
	}else{
		echo '/* '.t('There is no content in the specified file or there was an error processing it.').' */';	
	}

}