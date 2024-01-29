<?php
class ContribuerManager
    {
        private $_db; // Instance de PDO - objet de connexion au SGBD
        
        public function __construct($db) {
            $this->_db=$db;
        }

        // ajout de contributeurs au projet
        public function add($id_projet, $idmembre) {
			// requete d'ajout dans la BD
			$req = "INSERT INTO contribuer (id_projet,idmembre) VALUES (?,?)";
			$stmt = $this->_db->prepare($req);
			$res  = $stmt->execute(array($id_projet, $idmembre));		
			// pour debuguer les requêtes SQL
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {
				print_r($errorInfo);
			}
			return $res;
		}

		//suppression de l'association d'un contributeur au projet
		public function delete(Contribuer $projet) : bool {
			$req = "DELETE FROM contribuer WHERE id_projet = ?";
			$stmt = $this->_db->prepare($req);
			return $stmt->execute(array($projet->id_projet()));
		}


    }

?>