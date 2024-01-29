<?php
include "Modules/contribuer.php";
include "Models/contribuerManager.php";

class ContribuerController {
    
	private $contribuerManager; // instance du manager
	private $twig;
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {
		$this->contribuerManager = new ContribuerManager($db);
		$this->twig = $twig;
	}


  
}
?>