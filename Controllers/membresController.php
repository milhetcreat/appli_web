<?php
include "Modules/membre.php";
include "Models/membreManager.php";

class MembreController {
    private $membreManager;
    private $coursManager;
    private $categorieManager;


	private $projetsManager; // instance du manager
    private $twig;

	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {
		$this->membreManager = new MembreManager($db);
		$this->projetsManager = new ProjetsManager($db);
		$this->coursManager = new CoursManager($db);
		$this->categorieManager = new CategorieManager($db);
		$this->twig = $twig;
	}
        

	// connexion
	function membreConnexion($data) {
	// connection avec les informations des cookies s'il les valeurs existent
    if ((isset($_SESSION['acces']) && $_SESSION['acces'] == "non") && isset($_COOKIE['idmembre'])) {
        // Utilisation des valeurs des cookies pour remplir les champs
		$membre = $this->membreManager->verif_identification($_COOKIE['login']);
		if ($membre !== false) { // Accès autorisé : variable de session acces = oui
			$_SESSION['acces'] = "oui";
			$_SESSION['idmembre'] = $membre->idMembre();
			$_SESSION['admin'] = $membre->admin();
			$_SESSION['prenom'] = $membre->prenom();
		}
	
    }
	// si il n'y a pas de cookie enregistré :
	else{
		  // Vérification du login et mot de passe
		  $membre = $this->membreManager->verif_identification($_POST['login']);
		  if ($membre !== false && password_verify($_POST['passwd'], $membre->password())) { // Accès autorisé : variable de session acces = oui
			$_SESSION['acces'] = "oui";
			$_SESSION['idmembre'] = $membre->idMembre();
			$_SESSION['admin'] = $membre->admin();
			$_SESSION['prenom'] = $membre->prenom();
			if (isset($_POST['remember'])) {
			setcookie('login', $_POST['login'], time()+3600);
			setcookie('idmembre', $membre->idMembre(), time()+3600);
			}
	
			echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'prenom' => $_SESSION['prenom']));
		} else { // Accès non autorisé : variable de session acces = non
			$message = "Identification incorrecte";
			$_SESSION['acces'] = "non";
			$_SESSION['prenom'] = "doe";
	
			echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message, 'prenom' => $_SESSION['prenom']));
		}
	}	
    }
  

   



	// inscription
	function membreInscription($data) {
		$ok = $this->membreAjout($data);
		if ($ok["mail"] == false) {
			echo "L'adresse mail n'est pas celle de l'IUT !";
		}
		if ($ok["mdp"] == false) {
			echo "Le mot de passe doit contenir minimum 8 caractères et au moins une lettre majuscule, un chiffre et un caractère spécial !";
		} 
		else{
		    echo $this->twig->render('membre_connexion.html.twig',array('acces'=> $_SESSION['acces']));
		}
		return $ok ;
	}

	// Ajout d'un membre
	function membreAjout($data) {
		$membre = new Membre($_POST);
		$email = $membre->email();
		$iut = '/etu.iut-tlse3.fr/';
		$motDePasse = $membre->password();

		// vérification que l'email soit celle de l'iut
		if (preg_match($iut, $email)) {
			$mail = true;
		} else {
			$mail = false;
			echo "L'adresse mail n'est pas celle de l'IUT !";
		}
		// vérification que le mot de passe soit sécurisé :
		// vérification d'une longueur minale de 8 caractères
		if (strlen($motDePasse) < 8) {
			$mdp = false;
			echo"Le mot de passe doit contenir minimum 8 caractères !";
		}
		// Complexité : au moins une lettre majuscule, un chiffre et un caractère spécial
		if (!preg_match('/[A-Z]/', $motDePasse) || !preg_match('/[0-9]/', $motDePasse) || !preg_match('/[\W]/', $motDePasse)) {
			$mdp = false;
			echo "Le mot de passe doit contenir au moins une lettre majuscule, un chiffre et un caractère spécial !";
		} else {
			// Cryptage du mot de passe
			$motDePasseCrypte = password_hash($motDePasse, PASSWORD_DEFAULT);

			// Mettre le mot de passe crypté dans l'objet membre
			$membre->setPassword($motDePasseCrypte);
		
			// Ajout du membre
			$mdp = $this->membreManager->add($membre);
		}
		return array('mail' => $mail, 'mdp' => $mdp) ;
	}

	//suppression dans la BD d'un membre à partir de l'id choisi dans le tableau MON ESPACE
	function suppMembre() {
		$membre = new Membre($_POST);
		$ok = $this->membreManager->delete($membre);
		return $ok ;
	}


	// deconnexion
	function membreDeconnexion() {
		$_SESSION['acces'] = "non"; // acces non autorisé
		if (isset($_COOKIE['login'])){
			setcookie('login', $_COOKIE['login'], time()-3600);
			setcookie('idmembre', $_COOKIE['idmembre'], time()-3600);
		}
		echo $this->twig->render('index.html.twig',array('acces'=> $_SESSION['acces'])); 
	}


	// formulaire de connexion
	function membreFormulaire() {
		echo $this->twig->render('membre_connexion.html.twig',array('acces'=> $_SESSION['acces'])); 
	}


	// formulaire d'inscription
	function membreFormulaireInscription() {
		echo $this->twig->render('membre_inscription.html.twig',array('acces'=> $_SESSION['acces'])); 
	}

	// formulaire ajout utilisateur
	function AjoutMembreFormulaire() {
		echo $this->twig->render('membre_ajout.html.twig',array('acces'=> $_SESSION['acces'], 'admin'=> $_SESSION['admin'], 'prenom'=> $_SESSION['prenom'])); 
	}


	// liste les informations du membre connecté + ses projets + s'il est admin : cours, catégories et tous les membres (espace membre)
	function listeInfosMembre() {
		$membre = $this->membreManager->get($_SESSION['idmembre']);
		$projets = $this->projetsManager->getListMembre($_SESSION['idmembre']);
		$projets_valide = $this->projetsManager->getListNoValide();
		$membres = $this->membreManager->getList();
		$courss = $this->coursManager->getList();
		$categories = $this->categorieManager->getList();
		echo $this->twig->render('espace_membre.html.twig',array('membre'=>$membre, 'projets'=>$projets, 'membres'=>$membres, 'courss'=>$courss, 'categories'=>$categories, 'projets_valide'=>$projets_valide, 'acces'=> $_SESSION['acces'], 'admin'=> $_SESSION['admin'], 'prenom'=> $_SESSION['prenom'])); 
	}

	// formulaire modification des informations utilisateur connecté
	function membreFormulaireModif() {
		$membre = $this->membreManager->get($_SESSION['idmembre']);
		echo $this->twig->render('membre_modif.html.twig',array('membre'=>$membre, 'acces'=> $_SESSION['acces'], 'admin'=> $_SESSION['admin'], 'prenom'=> $_SESSION['prenom'])); 
	}

	// formulaire modification d'un utilisateur
	function membreFormModif() {
		$membre = $this->membreManager->get($_POST['idmembre']);
		echo $this->twig->render('membre_modif.html.twig',array('membre'=>$membre, 'acces'=> $_SESSION['acces'], 'admin'=> $_SESSION['admin'], 'prenom'=> $_SESSION['prenom'])); 
	}

	// modific	tion de l'utilisateur dans la base de donnée
	function modMembre() {
		$membre =  new Membre($_POST);
		$ok = $this->membreManager->update($membre);
		return $ok;
	}


	
}
