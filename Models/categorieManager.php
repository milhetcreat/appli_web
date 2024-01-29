<?php
class CategorieManager
    {
        private $_db; // Instance de PDO - objet de connexion au SGBD
        
		/** 
		* Constructeur = initialisation de la connexion vers le SGBD
		*/
        public function __construct($db) {
            $this->_db=$db;
        }

        // retourne l'ensemble des catégories
        public function getList() {
            $categories = array();
			$req = 'SELECT num_cat, intitule FROM Categorie';
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
				$categories[] = new Categorie($donnees);
			}
			return $categories;
        }

		// recherche une catégorie par rapport à son id
        public function get($num_cat) : Categorie {	
            $req = 'SELECT * FROM Categorie 
            WHERE Categorie.num_cat=?';
            $stmt = $this->_db->prepare($req);
            $stmt->execute(array($num_cat));
            // pour debuguer les requêtes SQL
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
            $categorie = new Categorie($stmt->fetch());
            return $categorie;
        }
        
       // retourne l'ensemble des Catégories pour un projet 
        public function getCat($idprojet) {
            $cats = array();
            $req = "SELECT Categorie.num_cat, Categorie.intitule
            FROM Categorie
            INNER JOIN attribuer ON attribuer.num_cat = Categorie.num_cat
            INNER JOIN Projet ON attribuer.id_projet = Projet.id_projet
            WHERE Projet.id_projet=?";
            $stmt = $this->_db->prepare($req);
            $stmt->execute(array($idprojet));
            // pour debuguer les requêtes SQL
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
            // recup des données
            while ($donnees = $stmt->fetch())
            {
                $cats[] = new Tag($donnees);
            }
            return $cats;
        }	

        // ajout d'une catégorie dans la BD
        public function add(Categorie $categorie) {
            // calcul d'un nouveau num_cat non utilisé = Maximum + 1
            $stmt = $this->_db->prepare("SELECT max(num_cat) AS maximum FROM Categorie");
            $stmt->execute();
            $categorie->setIdCat($stmt->fetchColumn()+1);

			// requete d'ajout dans la BD
			$req = "INSERT INTO Categorie (num_cat,intitule) VALUES (?,?)";
			$stmt = $this->_db->prepare($req);
			$res  = $stmt->execute(array($categorie->num_cat(), $categorie->intitule()));		
			// pour debuguer les requêtes SQL
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {
				print_r($errorInfo);
			}
			return $res;
		}

        //suppression d'une catégorie dans la base de données
		public function delete(Categorie $categorie) : bool {
			$req = "DELETE FROM Categorie WHERE num_cat = ?";
			$stmt = $this->_db->prepare($req);
			return $stmt->execute(array($categorie->num_cat()));
		}

        // modification d'une catégorie
		public function update(Categorie $categorie) : bool {
			$req = "UPDATE Categorie SET intitule = :intitule "
						. " WHERE num_cat = :num_cat";
	
			$stmt = $this->_db->prepare($req);
			$stmt->execute(array(":intitule" => $categorie->intitule(),
								":num_cat" => $categorie->num_cat() ));
			return $stmt->rowCount();
			
		}
    }

?>