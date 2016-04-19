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
			$this->loadview("MyDisques/disque.html",array("disques"=>$disque));
		}
	}

	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}
}

/*class ModelUtils{
	ModelUtils::sizeConverter("1Ko")
	$disque->getQuota()
	$disque->getSize()

} */ 
