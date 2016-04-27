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
		$this->loadView("MyDisques/disque.html", array("users"=>$user, "disques"=>$disques));
		ModelUtils::sizeConverter("Ko");
		





	}
	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}
}





