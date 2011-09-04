<?php defined('C5_EXECUTE') or die("Access Denied.");
	class MultiPageSelectorBlockController extends BlockController {
		
		protected $btDescription = "Select and order a specific pages.";
		protected $btName = "Multi-Page Selector";
		protected $btTable = 'btMultiPageSelector';
		protected $btInterfaceWidth = "400";
		protected $btInterfaceHeight = "300";
		
		
		function loadExistingLabels(){
			$sql = "SELECT DISTINCT label FROM btMultiPageSelector ORDER BY label ASC";	
			$db = Loader::db();
			$this->existingLabels = $db->getCol($sql);
			$this->set("existingLabels", $this->existingLabels);
		}
		
		function loadCollectionIDArray(){
			$sql = "SELECT cID FROM btMultiPageSelectorItem WHERE bID=".intval($this->bID).' ORDER BY position ASC';
			$db = Loader::db();
			$this->cIDArray=$db->getCol($sql);
			$this->set('cIDArray', $this->cIDArray);	
		}
		
		function loadMultiPageSelector(){
			$html = Loader::helper('html');			
			$mps = Loader::helper('form/multi_page_selector', 'multi_page_selector');
			$mps->addHeaderItems($this);
			$this->set('mps', $mps);	
		}
		
		function add(){
			$this->loadCollectionIDArray();
			$this->loadExistingLabels();
			$this->loadMultiPageSelector();
		}
		function edit(){
			$this->loadCollectionIDArray();
			$this->loadExistingLabels();
			$this->loadMultiPageSelector();	
		}
		function view(){
			$this->loadCollectionIDArray();	
		}
		
		
		function validate($data){
			$e = Loader::helper('validation/error');
			
			if(count($data['cIDArray']) < 1){
				$e->add(t('Select one or more pages.'));
			}
			
			if(empty($data['label'])){
				$e->add(t('Enter a label that describes the references.'));
			}
			//$e->add($this->pre($data, TRUE));
			return $e;
		}
		
		function save($data){
			$db = Loader::db();
			$pos = 0;
			
			$txt = Loader::helper('text');
			$data['label'] = $txt->sanitizeFileSystem(preg_replace("/\s+/", '_', trim($data['label'])));			
			
			//Delete existing pages -- what does this do to versioning?
			$db->query('DELETE FROM btMultiPageSelectorItem WHERE bID='.intval($this->bID));
			
			//Add the pages
			foreach($data['cIDArray'] as $cID){ 
				$vals = array(intval($this->bID),intval($cID), $pos);
				$db->query("INSERT INTO btMultiPageSelectorItem (bID,cID,position) values (?,?,?)",$vals);
				$pos++;
			}
			
			parent::save($data);
		}
		
		
		function delete(){
			$db = Loader::db();
			$db->query("DELETE FROM btMultiPageSelectorItem WHERE bID=".intval($this->bID));		
			parent::delete();
		}
		
		
		
		function pre($thing, $save=FALSE){
			$str = '<pre style="white-space:pre; border:1px solid #ccc; padding:8px; margin:0 0 8px 0;">'.print_r($thing, TRUE).'</pre>';
			if(!$save){
				echo $str;	
			}
			return $str;
		}
		
	}
	
?>