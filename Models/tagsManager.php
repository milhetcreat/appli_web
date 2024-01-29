<?php
class TagsManager
    {
        private $_db; // Instance de PDO - objet de connexion au SGBD
        
		/** 
		* Constructeur = initialisation de la connexion vers le SGBD
		*/
        public function __construct($db) {
            $this->_db=$db;
        }

        // retourne l'ensemble des tags
        public function getList() {
            $tags = array();
			$req = 'SELECT * FROM Tags';
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
				$tags[] = new Tag($donnees);
			}
			return $tags;
        }

        
       // retourne l'ensemble des tags pour un projet 
        public function getTags($idprojet) {
            $tags = array();
            $req = "SELECT Tags.id_tag, Tags.intitule
            FROM Tags
            INNER JOIN realiser ON realiser.id_tag = Tags.id_tag
            INNER JOIN Projet ON realiser.id_projet = Projet.id_projet
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
                $tags[] = new Tag($donnees);
            }
            return $tags;
        }	
    }

?>