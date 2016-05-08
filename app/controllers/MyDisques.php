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
        $cloud=$this->config->cloud; /* Accès à la config du Cloud */
		$user=Auth::getUser();
		$disques=micro\orm\DAO::getOneToMany($user, "disques");
		$disque=Disque::findfirst();
        /* Methodo A revoir */
		$nom=Auth::getUser();
		$occupation=ModelUtils::getDisqueOccupation($cloud,$disque);
        if(strlen($occupation)<=7){
            $occupation=round($occupation/ModelUtils::sizeConverter("Ko"),2);
            $unite="Ko";
        }else{
            $occupation=round($occupation/ModelUtils::sizeConverter("Mo"),2);
            $unite="Mo";
        }
        $this->loadView("MyDisques/disque.html", array("nom"=>$nom, "occup"=>$occupation));







	}
	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}
}





