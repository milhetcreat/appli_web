<?php
include "Modules/cours.php";
include "Models/coursManager.php";

class CoursController {
    
	private $coursManager; // instance du manager
	private $twig;
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {
		$this->coursManager = new CoursManager($db);
		$this->twig = $twig;
	}

	// formulaire ajout cours
	function AjoutCoursFormulaire() {
		echo $this->twig->render('cours_ajout.html.twig',array('acces'=> $_SESSION['acces'], 'prenom'=> $_SESSION['prenom'])); 
	}

	// Ajout d'un cours
	function coursAjout($data) {
		$cours = new Cours($_POST);
		$ok = $this->coursManager->add($cours);
		return $ok ;
	}

	//suppression dans la BD d'un cours à partir de l'id choisi dans le tableau MON ESPACE
	public function suppCours() {
		$cours = new Cours($_POST);
		$ok = $this->coursManager->delete($cours);
		return $ok ;
	}

	// formulaire modification d'un cours
	function coursFormModif() {
		$cours = $this->coursManager->get($_POST['id_cours']);
		echo $this->twig->render('cours_modif.html.twig',array('cours'=>$cours, 'acces'=> $_SESSION['acces'], 'admin'=> $_SESSION['admin'], 'prenom'=> $_SESSION['prenom'])); 
	}

	// modification du cours dans la base de donnée
	public function modCours() {
		$cours =  new Cours($_POST);
		$ok = $this->coursManager->update($cours);
		return $ok;
	}


  
}
?>