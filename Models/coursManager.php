<?php
class CoursManager
    {
        private $_db; // Instance de PDO - objet de connexion au SGBD
        
		/** 
		* Constructeur = initialisation de la connexion vers le SGBD
		*/
        public function __construct($db) {
            $this->_db=$db;
        }

        // retourne l'ensemble des cours
        public function getList() {
            $courss = array();
			$req = 'SELECT id_cours, intitule, semestre FROM Cours';
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
				$courss[] = new Cours($donnees);
			}
			return $courss;
        }

		// recherche un cours par rapport à son id
        public function get($id_cours) : Cours {	
            $req = 'SELECT * FROM Cours 
            WHERE Cours.id_cours=?';
            $stmt = $this->_db->prepare($req);
            $stmt->execute(array($id_cours));
            // pour debuguer les requêtes SQL
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
            $cours = new Cours($stmt->fetch());
            return $cours;
        }

        
        // recherche dans la BD le cours du projet lister en détails à partir de l'id du cours
        public function getCours($id_cours) : Cours {	
            $req = 'SELECT intitule, semestre FROM Cours 
            INNER JOIN Projet ON Cours.id_cours = Projet.id_cours 
            WHERE Projet.id_cours=?';
            $stmt = $this->_db->prepare($req);
            $stmt->execute(array($id_cours));
            // pour debuguer les requêtes SQL
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
            $cours = new Cours($stmt->fetch());
            return $cours;
        }
        
        // ajout d'un cours dans la BD
        public function add(Cours $cours) {
            // calcul d'un nouveau id_cours non utilisé = Maximum + 1
            $stmt = $this->_db->prepare("SELECT max(id_cours) AS maximum FROM Cours");
            $stmt->execute();
            $cours->setIdCours($stmt->fetchColumn()+1);

			// requete d'ajout dans la BD
			$req = "INSERT INTO Cours (id_cours,intitule,semestre) VALUES (?,?,?)";
			$stmt = $this->_db->prepare($req);
			$res  = $stmt->execute(array($cours->id_cours(), $cours->intitule(), $cours->semestre()));		
			// pour debuguer les requêtes SQL
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {
				print_r($errorInfo);
			}
			return $res;
		}

        //suppression d'un cours dans la base de données
		public function delete(Cours $cours) : bool {
			$req = "DELETE FROM Cours WHERE id_cours = ?";
			$stmt = $this->_db->prepare($req);
			return $stmt->execute(array($cours->id_cours()));
		}


		// modification d'un cours
		public function update(Cours $cours) : bool {
			$req = "UPDATE Cours SET intitule = :intitule, "
						. "semestre = :semestre "
						. " WHERE id_cours = :id_cours";
	
			$stmt = $this->_db->prepare($req);
			$stmt->execute(array(":intitule" => $cours->intitule(),
									":semestre" => $cours->semestre(),
									":id_cours" => $cours->id_cours() ));
			return $stmt->rowCount();
			
		}
    }

?>