<?php
include "Modules/attribuer.php";
include "Models/attribuerManager.php";

class AttribuerController {
    
	private $attribuerManager; // instance du manager
	private $twig;
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {
		$this->attribuerManager = new AttribuerManager($db);
		$this->twig = $twig;
	}


  
}
?>