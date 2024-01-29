<?php
include "Modules/categorie.php";
include "Models/categorieManager.php";

class CategorieController {
    
	private $categorieManager; // instance du manager
	private $twig;
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {
		$this->categorieManager = new CategorieManager($db);
		$this->twig = $twig;
	}

	// formulaire ajout catégorie
	function AjoutCategorieFormulaire() {
		echo $this->twig->render('categorie_ajout.html.twig',array('acces'=> $_SESSION['acces'], 'prenom'=> $_SESSION['prenom'])); 
	}

	// Ajout d'une catégorie
	function categorieAjout($data) {
		$categorie = new Categorie($_POST);
		$ok = $this->categorieManager->add($categorie);
		return $ok ;
	}

	//suppression dans la BD d'une catégorie à partir de l'id choisi dans le tableau MON ESPACE
	public function suppCategorie() {
		$categorie = new Categorie($_POST);
		$ok = $this->categorieManager->delete($categorie);
		return $ok ;
	}

	// formulaire modification d'une catégorie
	function categorieFormModif() {
		$categorie = $this->categorieManager->get($_POST['num_cat']);
		echo $this->twig->render('categorie_modif.html.twig',array('categorie'=>$categorie, 'acces'=> $_SESSION['acces'], 'admin'=> $_SESSION['admin'], 'prenom'=> $_SESSION['prenom'])); 
	}

	// modification du cours dans la base de donnée
	public function modCategorie() {
		$categorie =  new Categorie($_POST);
		$ok = $this->categorieManager->update($categorie);
		return $ok;
	}


  
}
?>