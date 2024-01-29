<?php
include "Modules/projets.php";
include "Models/projetsManager.php";

class ProjetsController {
    
	private $projetsManager; // instance du manager

	// instance des autres manager
	private $coursManager;
	private $tagsManager;
	private $categorieManager;

	private $membreManager;
	private $commentaireManager;

	private $realiserManager;
	private $attribuerManager;
	private $contribuerManager;

	private $twig;
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {
		$this->projetsManager = new ProjetsManager($db);
		$this->coursManager = new CoursManager($db);
		$this->tagsManager = new TagsManager($db);
		$this->categorieManager = new CategorieManager($db);
		$this->membreManager = new MembreManager($db);
		$this->commentaireManager = new CommentaireManager($db);
		$this->realiserManager = new RealiserManager($db);
		$this->attribuerManager = new AttribuerManager($db);
		$this->contribuerManager = new ContribuerManager($db);

		$this->twig = $twig;
	}
        
	//liste de tous les projets (chatgpt + mes modifications)
	public function listeProjets($currentPage) {
		$total = $this->projetsManager->Count();
		$elementsParPage = 3;
		$totalPages = ceil($total / $elementsParPage);
		$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$offset = ($currentPage - 1) * $elementsParPage ;
		$projets = $this->projetsManager->getList($offset, $elementsParPage);
		echo $this->twig->render('projet_liste.html.twig', array(
			'projets' => $projets,
			'totalPages' => $totalPages,
			'currentPage' => $currentPage,
			'acces' => $_SESSION['acces'],
			'prenom' => $_SESSION['prenom']
		)); 
	}

    // liste en détails tous les projets dans une page projet
    function listeDetailsProjets() {
		$projet = $this->projetsManager->get($_POST['id_projet']);
		$cours = $this->coursManager->getCours($_POST['id_cours']);
		$tags = $this->tagsManager->getTags($_POST['id_projet']);
		$categories = $this->categorieManager->getCat($_POST['id_projet']);
		$membres = $this->membreManager->getContributeur($_POST['id_projet']);
		$commentaires = $this->commentaireManager->getListProjet($_POST['id_projet']);
		if ($_SESSION['acces'] == 'oui') {
			$utilisateur = $this->membreManager->get($_SESSION['idmembre']);
		} else{
			$utilisateur = 'none';
		}
		echo $this->twig->render('liste_detailsProjets.html.twig', array('acces'=> $_SESSION['acces'], 'prenom'=> $_SESSION['prenom'],'projet'=>$projet, 'cours'=>$cours, 'tags'=>$tags, 'categories'=>$categories, 'membres'=>$membres, 'commentaires'=>$commentaires, 'utilisateur'=>$utilisateur)); 
	}

	// affichage du formulaire de recherche
	public function formRechercheProjet() {
		echo $this->twig->render('projets_recherche.html.twig', array('acces' => $_SESSION['acces'], 'prenom' => $_SESSION['prenom']));
    }
	

	// >> Code de Mr Pécatte
	// formulaire recherche de projet dans la BD
	public function rechercheProjetAJAX($motcle) {
		$projetsJSON = $this->projetsManager->search($motcle);
		return $projetsJSON;

	}

	// validation du projet
	public function valideProjet() {
		// $projet =  new Projet($_POST);
		$this->projetsManager->update($_POST['id_projet']);
	}


	// formulaire ajout
	public function formAjoutProjet() {
		$courss = $this->coursManager->getList();
		$tags = $this->tagsManager->getList();
		$categories = $this->categorieManager->getList();
		$contributeurs = $this->membreManager->getList();
		echo $this->twig->render('projet_ajout.html.twig',array('contributeurs'=>$contributeurs, 'categories'=>$categories, 'tags'=>$tags, 'courss'=>$courss, 'acces'=> $_SESSION['acces'],'idmembre'=>$_SESSION['idmembre'], 'prenom'=> $_SESSION['prenom'])); 
	}

	// ajout dans la BD d'un projet + de toutes les clés étrangères à partir du form
	public function ajoutProjet() {
		$projet = new Projets($_POST);

		// --------- recupération du fichier photo
		if ($_FILES["img"]["error"]==UPLOAD_ERR_OK) { // verif si pas d'erreur
			// verifier le type de fichier
		
			// déplacer le fichier temporaire sur un repertoire du serveur web
			$uploaddir = "./images/"; // chemin du dossier où ranger les fichiers
			$uploadfile = $uploaddir . basename($_FILES["img"]["name"]); // nom initial du fichier
			// on déplace le fichier du dossier temporaire du serveur web
			// vers le dossier approprié du site web et on renomme le fichier
			if (!move_uploaded_file($_FILES["img"]["tmp_name"], $uploadfile)) {
			echo "pb lors du telechargement";
			}
		}
		else {
			// traitement des erreurs
			echo "pas de fichier";
		}

		$projet->setImg($uploadfile);
		$ok_projet = $this->projetsManager->add($projet);

		// récupértation de l'id du projet
		$id_projet = $projet->id_projet();

		// récupération et ajout des checkbox tag
		if (!isset($_POST["tag"])) { // si aucun tag n'a été choisi
			echo "vous n'avez aucun tag";
		}
		else {
			foreach ($_POST["tag"] as $tag) { $this->realiserManager->add($id_projet, $tag);}
		}

		// récupération et ajout des checkbox catégorie
		if (!isset($_POST["cat"])) { // si aucune catégorie n'a été choisi
			echo "vous n'avez aucun catégorie";
		}
		else {
			foreach ($_POST["cat"] as $cat) { $this->attribuerManager->add($id_projet, $cat);}
		}

		// récupération et ajout des checkbox contributeur
		if (!isset($_POST["membre"])) { // si aucun membre n'a été choisi
			echo "vous n'avez aucun membre sur le projet";
		}
		else {
			foreach ($_POST["membre"] as $mem) { $this->contribuerManager->add($id_projet, $mem);}
		}
	}

	//suppression dans la BD d'un projet à partir de l'id choisi dans le tableau MON ESPACE
	public function suppProjet() {
		$projet = new Realiser($_POST);
		$ok_realiser = $this->realiserManager->delete($projet);
		$projet = new Attribuer($_POST);
		$ok_attribuer = $this->attribuerManager->delete($projet);
		$projet = new Contribuer($_POST);
		$ok_contribuer = $this->contribuerManager->delete($projet);
		$projet = new Projets($_POST);
		$ok_projet = $this->projetsManager->delete($projet);
	}

	// form de saisi des nouvelles valeurs du projet à modifier
	public function saisieModProjet() {
		$courss = $this->coursManager->getList();
		$tags = $this->tagsManager->getList();
		$categories = $this->categorieManager->getList();
		$contributeurs = $this->membreManager->getList();
		$projet =  $this->projetsManager->get($_POST["id_projet"]);
		echo $this->twig->render('projet_modification.html.twig',array('projet'=>$projet, 'courss'=>$courss, 'tags'=>$tags, 'categories'=>$categories, 'contributeurs'=>$contributeurs, 'acces'=> $_SESSION['acces'], 'prenom'=> $_SESSION['prenom'])); 
	}

	// modifie le projet au click
	public function modProjet() {
		$this->suppProjet();
		$this->ajoutProjet();
	}




}