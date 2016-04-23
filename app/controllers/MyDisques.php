<?php
use micro\controllers\Controller;
use micro\js\Jquery;
use micro\utils\RequestUtils;

class MyDisques extends Controller{
	public function initialize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
        }

	}
	public function index() {
		echo Jquery::compile();
		$user=Auth::getUser();
		$disques=micro\orm\DAO::getOneToMany($user, "disques");
		foreach($disques as $disque){
			$this->loadview("MyDisques/disque.html",array("disque"=>$disque));
		}
        $occupation=$disque->getOccupation();
		$disque->getSize();
		$disque->getQuota();
		ModelUtils::sizeConverter("Mo");
		$taille=$disque->getOccupation();
		DirectoryUtils::formatBytes(getOccupation,2); // Pas bon 


	}
	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}
}





