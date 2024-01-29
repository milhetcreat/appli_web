<?php
class RealiserManager
    {
        private $_db; // Instance de PDO - objet de connexion au SGBD
        
        public function __construct($db) {
            $this->_db=$db;
        }

        // ajout de tags à un projet
        public function add($id_projet, $id_tag) {
			// requete d'ajout dans la BD
			$req = "INSERT INTO realiser (id_projet,id_tag) VALUES (?,?)";
			$stmt = $this->_db->prepare($req);
			$res  = $stmt->execute(array($id_projet, $id_tag));		
			// pour debuguer les requêtes SQL
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {
				print_r($errorInfo);
			}
			return $res;
		}

		//suppression de l'association du tag au projet
		public function delete(Realiser $projet) : bool {
			$req = "DELETE FROM realiser WHERE id_projet = ?";
			$stmt = $this->_db->prepare($req);
			return $stmt->execute(array($projet->id_projet()));
		}

		// retourne dans le formulaire modifier check si présent dans la BD
		// >>>>>> CODE CHAT GPT
		// public function projetExiste($id_projet, $id_tag) {
		// 	$req = "SELECT COUNT(*) FROM realiser WHERE id_projet = ? AND id_tag = ? ";
		// 	$stmt = $this->_db->prepare($req);
		// 	$stmt->execute(array($id_projet, $id_tag));
		// 	$count = $stmt->fetchColumn();
		// 	return $count > 0;
		// }


    }

?>