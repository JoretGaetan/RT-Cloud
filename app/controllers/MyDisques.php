<?php
use micro\controllers\Controller;
use micro\js\Jquery;
use micro\utils\RequestUtils;

class MyDisques extends Controller{
	public function initialize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser())); }

		$user=Auth::getUser();
		$disques=DAO::getOneToMany($user, "disques");
		foreach($disques as $disque){
				$this->loadview("MyDisques/disque.html",array("disques"=>$disques));
		}

	}
	public function index() {
		echo Jquery::compile();
	}

	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}

}

