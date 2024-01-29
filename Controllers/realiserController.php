<?php
include "Modules/realiser.php";
include "Models/realiserManager.php";

class RealiserController {
    
	private $realiserManager; // instance du manager
	private $twig;
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {
		$this->realiserManager = new RealiserManager($db);
		$this->twig = $twig;
	}


  
}
?>