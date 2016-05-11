<?php
class Disques extends \_DefaultController {

	public function __construct(){
		parent::__construct();
		$this->title="Disques";
		$this->model="Disque";
	}

	public function frm($id=NULL){
		$disque=$this->getInstance($id);
		$disabled="";
		$this->loadView("formulaire/creation_disque.html",array("disque"=>$disque,"disabled"=>$disabled));
	}

	public function update(){
		// Si un ID et un nom sont passés en paramètres,on met à jour le disque **
		if($_POST["id"] && $_POST['nom']) {
			// On recupère le chemin ABSOLU du dossier (disque) grace à l'ancien nom du disque
			$oldfolder = DAO::getOne('Disque', $_POST['id'])->getNom();
			$basepath = (dirname(dirname(__DIR__))."/files/".$GLOBALS['config']['cloud']['prefix'].Auth::getUser()->getLogin().'/');
			$actualpath = $basepath.$oldfolder;
			$newpath = $basepath.$_POST['nom'];
			// On vérifie le fonctionnement du chemin
			try {
				rename($actualpath, $newpath);
			} catch (Exception $e) {
				die("Erreur pour renommer le dossier");
			}
			// Sinon, on créer un disque
		} else {
			if ($_POST['nom']) {
				// On recupère le chemin ABSOLU du dossier (disque) comme au dessus
				$basepath = (dirname(dirname(__DIR__))."/files/".$GLOBALS['config']['cloud']['prefix'].Auth::getUser()->getLogin().'/');
				$newpath = $basepath.$_POST['nom'];
				// Exeception pour observer la création du disque
				try {
					mkdir($newpath);
				} catch (Exception $e) {
					die("Erreur de créer le dossier");
				}
			}
		}
		// Update du DefaultController permet de mettre à jour les paramètres sur la BDD 
		parent::update();
	}


	/* (non-PHPdoc)
	 * @see _DefaultController::setValuesToObject()
	 */
	protected function setValuesToObject(&$object) {
		parent::setValuesToObject($object);
		$object->setUtilisateur(Auth::getUser());
	}

}