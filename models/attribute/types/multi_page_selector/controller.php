<?php defined('C5_EXECUTE') or die("Access Denied.");

Loader::model('attribute/types/default/controller');

class MultiPageSelectorAttributeTypeController extends DefaultAttributeTypeController  {

	protected $searchIndexFieldDefinition = 'X NULL';

	public function form() {		
		$this->set('mps', Loader::helper('form/multi_page_selector', 'multi_page_selector'));
		$this->getValue();
	}
	
	public function getValue(){
		$sql = "SELECT cID FROM atMultiPageSelectorItem WHERE avID=".intval($this->getAttributeValueID()).' ORDER BY position ASC';
		$db = Loader::db();
		$this->cIDArray = $db->getCol($sql);
		$this->set('cIDArray', $this->cIDArray);
		return $this->cIDArray;	
	}
	
	public function validateForm($data){
		$e = Loader::helper('validation/error');
		
		return (count($data['cIDArray']) > 1);
	}
	
	public function saveForm($data){
		//Log::addEntry(print_r($data, TRUE));
		$this->saveValue($data['cIDArray']);
	}
	
	
	public function saveValue($cIDArray){		
		if(count($cIDArray) > 0){
			$db = Loader::db();
			$avID = intval($this->getAttributeValueID());
			//Delete existing pages 
			$db->query('DELETE FROM atMultiPageSelectorItem WHERE avID IN('.$avID.',0)');
			
			//Add the pages
			$pos = 0;
			foreach($cIDArray as $cID){
				$vals = array($avID,intval($cID), $pos);
				$db->query("INSERT INTO atMultiPageSelectorItem (avID,cID,position) values (?,?,?)",$vals);
				//Log::addEntry(print_r($vals, TRUE));
				$pos++;
			}				
		}
	}
	
	public function deleteValue() {
		$db = Loader::db();
		$db->query("DELETE FROM atMultiPageSelectorItem WHERE avID=".intval($this->getAttributeValueID()));		
		parent::deleteValue();
	}
	
	
	
	function pre($thing, $save=FALSE){
		$str = '<pre style="white-space:pre; border:1px solid #ccc; padding:8px; margin:0 0 8px 0;">'.print_r($thing, TRUE).'</pre>';
		if(!$save){
			echo $str;	
		}
		return $str;
	}

}