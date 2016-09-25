<?php
if (!trait_exists('clsPluginBase')) {
	abstract class clsPluginBase extends Plugin_Core implements IclsStatusBase{
		public abstract  function __init();
		public abstract function __setDbData();
		
		public function ___init(){
			$this->isActive = 1;
			$this->isDelete = 0;
			$this->copyof = 0;
			$this->setSortOrder(0);
			$this->setDescription(' ');
		}
		/* Implement IMMStatus Base */
		public function setDescription($description){
			$this->description = $description;
		}
		public function getDescription($uniqueid = null){
			try{
				if (is_null($uniqueid)) {
					return $this->description;
				}
				else {
					$this->uniqueid = $uniqueid;
					$this->__init();
					return $this->description;
				}
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		public function setSortOrder($sortorder){
			$this->sortOrder = $sortorder;
		}
		public function getSortOrder($uniqueid = null){
			try{
				if (is_null($uniqueid)) {
					return $this->sortOrder;
				}
				else {
					$this->uniqueid = $uniqueid;
					$this->__init();
					return $this->sortOrder;
				}
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}		
		
		public function setActive($active){
			$this->isActive = $active;
		}
		public function IsActive($uniqueid){
			try{
				if (is_null($uniqueid)) {
					return $this->isActive;
				}
				else {
					$this->uniqueid = $uniqueid;
					$this->__init();
					return $this->isActive;
				}
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		public function setDelete($delete){
			$this->isDelete = $delete;
		}
		public function IsDelete($uniqueid){
			try{
				if (is_null($uniqueid)) {
					return $this->isDelete;
				}
				else {
					$this->uniqueid = $uniqueid;
					$this->__init();
					return $this->isDelete;
				}
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		/* Implement IMMStatus Base */
	}
}
?>