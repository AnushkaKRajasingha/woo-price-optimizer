<?php
/**
 * @author Anushka Rajasingha
 * @url http://www.anushkar.com
 * @date 02/27/2015
 *
 */
if(!interface_exists ('IclsStatusBase')){
	interface IclsStatusBase{
		public function setDescription($description);
		public function getDescription($uniqueid = null);
		public function setActive($active);
		public function IsActive($uniqueid);
		public function setDelete($delete);
		public function IsDelete($uniqueid);
		public function setSortOrder($sortorder);
		public function getSortOrder($uniqueid = null);
	}
}