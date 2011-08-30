<?php
	$nh = Loader::helper('navigation');
	echo "<ul>";
	foreach($cIDArray as $cID){
		$page = Page::getByID($cID);
		if(is_object($page)){
			$name = $page->getCollectionName();
			$path = $nh->getLinkToCollection($page);
			echo "<li><a href=\"$path\">$name</a></li>";	
		}
	}
	echo "</ul>";
?>
