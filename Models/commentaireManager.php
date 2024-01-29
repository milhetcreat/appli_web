<?php
class CommentaireManager
    {
        private $_db; // Instance de PDO - objet de connexion au SGBD
        
		/** 
		* Constructeur = initialisation de la connexion vers le SGBD
		*/
        public function __construct($db) {
            $this->_db=$db;
        }

        // retourne l'ensemble des commentaires présents dans la BD pour un projet
        public function getListProjet($id_projet) {
            $commentaires = array();
            $req = "SELECT Commentaire.id_com, Commentaire.description, membre.nom, membre.prenom, membre.idmembre
            FROM Commentaire
            INNER JOIN Projet ON Commentaire.id_projet = Projet.id_projet
			INNER JOIN membre ON membre.idmembre = Commentaire.idmembre 
            WHERE Projet.id_projet=?";
            $stmt = $this->_db->prepare($req);
            $stmt->execute(array($id_projet));
            // pour debuguer les requêtes SQL
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
            // recup des données
            while ($donnees = $stmt->fetch())
            {
                $commentaires[] = new Commentaire($donnees);
            }
            return $commentaires;
        }

        // ajout d'un commentaire dans la BD
        public function add(Commentaire $commentaire, $idmembre) {
            // calcul d'un nouveau id_com non utilisé = Maximum + 1
            $stmt = $this->_db->prepare("SELECT max(id_com) AS maximum FROM Commentaire");
            $stmt->execute();
            $commentaire->setIdCom($stmt->fetchColumn()+1);

			// requete d'ajout dans la BD
			$req = "INSERT INTO Commentaire (id_com,description, id_projet, idmembre) VALUES (?,?, ?, ?)";
			$stmt = $this->_db->prepare($req);
			$res  = $stmt->execute(array($commentaire->id_com(), $commentaire->description(), $commentaire->id_projet(), $commentaire->idMembre()));		
			// pour debuguer les requêtes SQL
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {
				print_r($errorInfo);
			}
			return $res;
		}
    }

?>