<?php
use micro\controllers\Controller;
use micro\js\Jquery;
use micro\utils\RequestUtils;

class MyDisques extends Controller{
	public function initialize()
	{
		if(!RequestUtils::isAjax())
		{
			$this->loadView("main/vHeader.html",array("infoUser"=>Auth::getInfoUser()));
        }

	}
	public function index() {
		if (Auth::isAuth()==True)
		{
			$user = Auth::getUser(); //Dection de l'utilisateur
			$disques=micro\orm\DAO::getOneToMany($user, "disques"); // Recuperation Paramettre Disque
			$this->loadView("MyDisques/entete.html", array("user"=>$user));
				foreach ($disques as $disque)
				{
					$nom = $disque->getNom();
					$quota = DirectoryUtils::formatBytes($disque->getQuota());
					$size = DirectoryUtils::formatBytes($disque->getSize());
					$occupation = $disque->getOccupation();
					$id= $disque->getId();
					$this->loadView("MyDisques/disque.html",array("nom"=>$nom, "quota"=>$quota, "size"=>$size, "occupation"=>$occupation,"id"=>$id ));
				}
			echo Jquery::compile();
		}
		else
		{
			$this->loadView("MyDisques/ConnexionAbs.html");
		}
	}


	public function finalize()
	{
		if(!RequestUtils::isAjax())
		{
			$this->loadView("main/vFooter.html");
		}
	}
}





