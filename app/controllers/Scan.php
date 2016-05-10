<?php
use micro\js\Jquery;
/**
 * Contrôleur permettant d'afficher/gérer 1 disque
 * @author jcheron
 * @version 1.1
 * @package cloud.controllers
 */
class Scan extends BaseController {

	public function index(){

	}

	/**
	 * Affiche un disque
	 * @param int $idDisque
	 */
	public function show($idDisque)
    {
		$disque=micro\orm\DAO::getOne("Disque",$idDisque);
		$services = micro\orm\DAO::getManyToMany( $disque, "services");
		$user = $disque->getUtilisateur()->getLogin(); /* Récupération de l'utilisateur actuellement connecte*/
		$diskName = $disque->getNom();
		$quota = DirectoryUtils::formatBytes($disque->getQuota());
		$size=DirectoryUtils::formatBytes($disque->getSize());
		$occupation = $disque->getOccupation();
		$tarif =  $disque->getTarif();
		$this->loadView("scan/vFolder.html", array("idDisque" => $idDisque, "user" => $user, "nom_disque" => $diskName, "taille" => $size,
			"occupation" => $occupation, "quota" => $quota, "tarif" => $tarif, "services" => $services,"disque" => $disque ));
		
		Jquery::executeOn("#ckSelectAll", "click", "$('.toDelete').prop('checked', $(this).prop('checked'));$('#btDelete').toggle($('.toDelete:checked').length>0)");
        Jquery::executeOn("#btUpload", "click", "$('#tabsMenu a:last').tab('show');");
        Jquery::doJqueryOn("#btDelete", "click", "#panelConfirmDelete", "show");
        Jquery::postOn("click", "#btConfirmDelete", "scan/delete", "#ajaxResponse", array("params" => "$('.toDelete:checked').serialize()"));
        Jquery::doJqueryOn("#btFrmCreateFolder", "click", "#panelCreateFolder", "toggle");
        Jquery::postFormOn("click", "#btCreateFolder", "Scan/createFolder", "frmCreateFolder", "#ajaxResponse");
        Jquery::execute("window.location.hash='';scan('" . $diskName . "')", true);
        echo Jquery::compile();
    }
	/*public function changetarif()
	{
		$valid_input = ['disqueId', 'userId', 'tarif'];
		if(!empty($_POST)) {
			foreach ($_POST as $input => $v) {
				if (!in_array($input, $valid_input)) {
					//TODO Error
					return false;
				}
			}

			$disque = DAO::getOne('disque', 'id = '. $_POST['disqueId']);
			$disqueTarif = DAO::getOne('disquetarif', 'idDisque = '. $_POST['diskId']);
			echo '<pre>';
			var_dump($disk->getOccupation() / 100 * $disqueTarif->getTarif()->getQuota());
			ModelUtils::sizeConverter();
			$tarif = DAO::getOne('tarif', 'id = '. $_POST['tarif']);

			$disqueTarif->setTarif($tarif);
			DAO::update($disqueTarif);

			if (DAO::update($disqueTarif)) {
				header('Location: /RT-Cloud/Scan/show/' . $_POST['disqueId']);
				return false;
			} else
				echo '<div class="alert alert-danger">Une erreur est survenue, veuillez rééssayer ultérieurement</div>';
		}
	}*/ 

	public function files($dir="Datas"){
		$cloud=$GLOBALS["config"]["cloud"];
		$root=$cloud["root"].$cloud["prefix"].Auth::getUser()->getLogin()."/";
		$response = DirectoryUtils::scan($root.$dir,$root);

		header('Content-type: application/json');
		echo json_encode(array(
				"name" => $dir,
				"type" => "folder",
				"path" => $dir,
				"items" => $response,
				"root" => $root
		));
	}

	public function upload(){
		$allowed = array('png', 'jpg', 'gif','zip');

		if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

			$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

			if(!in_array(strtolower($extension), $allowed)){
				echo '{"status":"error"}';
				exit;
			}

			if(move_uploaded_file($_FILES['upl']['tmp_name'], $_POST["activeFolder"].'/'.$_FILES['upl']['name'])){
				echo '{"status":"success"}';
				exit;
			}
		}

		echo '{"status":"error"}';
		exit;
	}

	/**
	 * Supprime le fichier dont le nom est fourni dans la clé toDelete du $_POST
	 */
	public function delete(){
		if(array_key_exists("toDelete", $_POST)){
			foreach ($_POST["toDelete"] as $f){
				unlink(realpath($f));
			}
			echo Jquery::execute("scan()");
			echo Jquery::doJquery("#panelConfirmDelete", "hide");

		}
	}

	/**
	 * Crée le dossier dont le nom est fourni dans la clé folderName du $_POST
	 */
	public function createFolder(){
		if(array_key_exists("folderName", $_POST)){
			$pathname=$_POST["activeFolder"].DIRECTORY_SEPARATOR.$_POST["folderName"];
			if(DirectoryUtils::mkdir($pathname)===false){
				$this->showMessage("Impossible de créer le dossier `".$pathname."`", "warning");
			}else{
				Jquery::execute("scan();",true);
			}
			Jquery::doJquery("#panelCreateFolder", "hide");
			echo Jquery::compile();
		}
	}

	/**
	 * Affiche un message dans une alert Bootstrap
	 * @param String $message
	 * @param String $type Class css du message (info, warning...)
	 * @param number $timerInterval Temps d'affichage en ms
	 * @param string $dismissable Alert refermable
	 * @param string $visible
	 */
	public function showMessage($message,$type,$timerInterval=5000,$dismissable=true){
		$this->loadView("main/vInfo",array("message"=>$message,"type"=>$type,"dismissable"=>$dismissable,"timerInterval"=>$timerInterval,"visible"=>true));
	}
}
