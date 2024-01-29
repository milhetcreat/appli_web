<?php
class AttribuerManager
    {
        private $_db; // Instance de PDO - objet de connexion au SGBD
        
        public function __construct($db) {
            $this->_db=$db;
        }

        // ajout de catégories au projet
        public function add($id_projet, $num_cat) {
			// requete d'ajout dans la BD
			$req = "INSERT INTO attribuer (id_projet,num_cat) VALUES (?,?)";
			$stmt = $this->_db->prepare($req);
			$res  = $stmt->execute(array($id_projet, $num_cat));		
			// pour debuguer les requêtes SQL
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {
				print_r($errorInfo);
			}
			return $res;
		}

		//suppression de l'association de la catégorie au projet
		public function delete(Attribuer $projet) : bool {
			$req = "DELETE FROM attribuer WHERE id_projet = ?";
			$stmt = $this->_db->prepare($req);
			return $stmt->execute(array($projet->id_projet()));
		}

    }

?>