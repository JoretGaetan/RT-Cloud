<?php
use micro\orm\DAO;
class Admin extends \BaseController {

	public function index() {

		$this->loadView("Administration/pageaccueil.html");

	}

	public function  users() {
		$users=\micro\orm\DAO::getAll("Utilisateur");
		$this->loadView("Administration/entete.html");
		foreach ($users as $user) {
			$nom =$user->getLogin();
			$this->loadView("Administration/AccesDisk.html", array( "nom"=>$nom));
		}

	}
}