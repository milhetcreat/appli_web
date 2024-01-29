<?php
include "Modules/commentaire.php";
include "Models/commentaireManager.php";

class CommentaireController {
    
	private $commentaireManager; // instance du manager
	private $membreManager; //instance du manager membre
	
	private $twig;
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {
		$this->commentaireManager = new CommentaireManager($db);
		$this->membreManager = new MembreManager($db);
		$this->twig = $twig;
	}

	// Ajout d'un commentaire
	function commentaireAjout($data, $idmembre) {
		$commentaire = new Commentaire($_POST);
		$ok = $this->commentaireManager->add($commentaire, $idmembre);
		return $ok ;
	}	


  
}
?>