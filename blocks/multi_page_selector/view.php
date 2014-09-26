<?php
	$nh = Loader::helper('navigation');
	echo '<ul class="b'.$bID.'" class="multi-page-list">';
	foreach($cIDArray as $cID){
		$page = Page::getByID($cID);
		if(is_object($page)){
			$p = new Permissions($page);
			if($p->canRead()) {
				$name = $page->getCollectionName();
				$handle = $page->getCollectionHandle();
				$path = $nh->getLinkToCollection($page);
				echo '<li class="'.$handle.'"><a href="'.$path.'">'.$name.'</a></li>';	
			}
		}
	}
	echo '</ul>';
?>
