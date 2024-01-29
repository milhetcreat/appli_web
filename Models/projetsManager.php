<?php

class ProjetsManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = inprojetalisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}


	// recherche dans la BD d'un projet à partir de son id
    public function get($id_projet) : Projets {	
        $req = 'SELECT id_projet, titre,description, img, lien_demo, lien_source, id_cours FROM Projet WHERE id_projet=? AND valide = 1';
        $stmt = $this->_db->prepare($req);
        $stmt->execute(array($id_projet));
        // pour debuguer les requêtes SQL
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != 0) {
            print_r($errorInfo);
        }
        $projet = new Projets($stmt->fetch());
        return $projet;
    }

	// affichage des projets non-valides
	public function getListNoValide() {
		$projets = array();
		$req = "SELECT * FROM Projet WHERE valide = 0 ";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$projets[] = new Projets($donnees);
		}
		return $projets;
	}
	
	// validation d'un projet
	public function update($id_projet) : bool {
		$req = "UPDATE Projet
		SET valide = 1
		WHERE id_projet = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($id_projet));
		return $stmt->rowCount();
	}

	// compte le nombre de ligne d'un projet
	// >>> code chatgpt avec mes modifications
	public function Count() {
		$req = "SELECT COUNT(*) as total FROM Projet WHERE valide = 1 ";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		
		// Pour déboguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		// Récupération du nombre total
		$total = $stmt->fetch(PDO::FETCH_ASSOC);

		// Vérifiez si $total est défini avant de le retourner
		return isset($total['total']) ? $total['total'] : 0;
	}
         	
		
	/**
	* retourne l'ensemble des projets présents dans la BD en affichant 3 projets par page (si valide)
	* @return Projets[]
	*/
	public function getList($offset, $elementsParPage) {
		// >>> fin du code CHATgpt
		$projets = array();
		$req = "SELECT * FROM Projet WHERE valide = 1 LIMIT $offset, $elementsParPage";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$projets[] = new Projets($donnees);
		}
		return $projets;
	}

	// recherche dans la BD
	// >> Code de Mr Pécatte
	public function search(string $motcle) {
		$req = "SELECT id_projet, titre, description, id_cours FROM Projet WHERE valide= 1 AND titre LIKE ? OR description LIKE ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array("%" . $motcle . "%", "%" . $motcle . "%"));

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
    }
	
	// ajout d'un projet dans la BD 
	public function add(Projets $projet) {
		// calcul d'un nouveau code de projet non utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(id_projet) AS maximum FROM Projet");
		$stmt->execute();
		$projet->setIdProjet($stmt->fetchColumn()+1);
		
		// requete d'ajout dans la BD
		$req = "INSERT INTO Projet (id_projet,titre,description,img,lien_demo,lien_source,id_cours,valide) VALUES (?,?,?,?,?,?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($projet->id_projet(), $projet->titre(), $projet->description(), $projet->img(), $projet->lien_demo(), $projet->lien_source(), $projet->id_cours(), $projet->valide()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	// suppression d'un projet dans la BD
	public function delete(Projets $projet) : bool {
		$req = "DELETE FROM Projet WHERE id_projet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($projet->id_projet()));
	}


	// retourne l'ensemble des projets présents dans la BD pour un membre
	public function getListMembre($idmembre) {
		$projets = array();
		$req = "SELECT Projet.id_projet, titre, description, img, lien_demo, lien_source, id_cours
		FROM Projet
		INNER JOIN contribuer ON Projet.id_projet = contribuer.id_projet
		INNER JOIN membre ON contribuer.idmembre = membre.idmembre
		WHERE membre.idmembre=? AND Projet.valide = 1";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idmembre));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch())
		{
			$projets[] = new Projets($donnees);
		}
		return $projets;
	}


}
?>