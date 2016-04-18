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
			$this->loadview("MyDisques/disque.html",array("disques"=>$disques));
		}
	}

	public function finalize(){
		if(!RequestUtils::isAjax()){
			$this->loadView("main/vFooter.html");
		}
	}

}

class ModelUtils {
    public static integer() {
        getDisqueOccupation( config $cloud, Disque $disque)

}
    public static Tarif{
        getDisqueTarif( Disque $disque )
}
    public static integer{
        sizeConverter( String $unit )
}
    public static string{
        arrayAsHtml( array $array, string $mainTag = "div", string $tag = "span class='label label-info'", string $sep = "&nbsp;" )
}

}
