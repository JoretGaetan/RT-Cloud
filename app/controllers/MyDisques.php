<?php
use micro\controllers\Controller;
use micro\js\Jquery;
use micro\utils\RequestUtils;

class MyDisques extends Controller{
	public function initialize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
            $user=Auth::getUser();
            $this->loadView("main/vHeader.html",array("user"=>$user));  #VERIFICATION // LA VUE EST FAUSSE 
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