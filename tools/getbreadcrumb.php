<?php 
defined('C5_EXECUTE') or die("Access Denied.");

if(isset($_REQUEST['cID'])){
	
	$page = Page::getByID($_REQUEST['cID']);
	if($page) {
		Loader::helper('form/multi_page_selector', 'multi_page_selector');
		echo FormMultiPageSelectorHelper::getBreadcrumb($page);
	}
}

?>