<?php

class MembreManager
    {
        private $_db; // Instance de PDO - objet de connexion au SGBD
        
		/** 
		* Constructeur = inmembrealisation de la connexion vers le SGBD
		*/
        public function __construct($db) {
            $this->_db=$db;
        }
		
		/**
		* verification de l'identité d'un membre (Login/password)
		* @param string $login
		* @param string $password
		* @return membre si authentification ok, false sinon
		*/
		public function verif_identification($login) {
			$req = "SELECT idmembre, nom, prenom, admin, password FROM membre WHERE email=:login";
			$stmt = $this->_db->prepare($req);
			$stmt->execute(array(":login" => $login));
			if ($data=$stmt->fetch()) { 
				$membre = new Membre($data);
				return $membre;
				}
			else return false;
		}

		// public function verif_cookie($login, $password) {
		// 	$req = "SELECT * FROM membre WHERE email = ? AND password = ?";
		// 	$stmt = $this->_db->prepare($req);
		// 	$stmt->execute(array($_COOKIE['email'], $_COOKIE['password']));
		// 	if ($data=$stmt->fetch()) { 
		// 		$membre = new Membre($data);
		// 		return $membre;
		// 		}
		// 	else return false;
		//  }

		// ajout d'un membre dans la BD lors de l'inscription
		public function add(Membre $membre) {
			// requete d'ajout dans la BD
			$req = "INSERT INTO membre (idmembre,nom,prenom,email,password,anneenaissance,sexe,telportable,admin) VALUES (?,?,?,?,?,?,?,?,?)";
			$stmt = $this->_db->prepare($req);
			$res  = $stmt->execute(array($membre->nom(), $membre->nom(), $membre->prenom(), $membre->email(), $membre->password(), $membre->anneeNaissance(), $membre->sexe(), $membre->telPortable(), $membre->admin()));		
			// pour debuguer les requêtes SQL
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {
				print_r($errorInfo);
			}
			return $res;
		}

		//suppression d'un membre dans la base de données
		public function delete(Membre $membre) : bool {
			$req = "DELETE FROM membre WHERE idmembre = ?";
			$stmt = $this->_db->prepare($req);
			return $stmt->execute(array($membre->idMembre()));
		}

		// recherche dans la BD les informations du membre connecté à partir de son id
		public function get($idmembre) : Membre {	
			$req = 'SELECT idmembre,nom,prenom, email, anneenaissance, sexe, telportable, admin, password FROM membre WHERE idmembre=?';
			$stmt = $this->_db->prepare($req);
			$stmt->execute(array($idmembre));
			// pour debuguer les requêtes SQL
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {
				print_r($errorInfo);
			}
			$membre = new Membre($stmt->fetch());
			return $membre;
	
		}

		// retourne l'ensemble des membres qui ont participé à un projet 
        public function getContributeur($idprojet) {
            $cats = array();
            $req = "SELECT nom, prenom
            FROM membre
            INNER JOIN contribuer ON contribuer.idmembre = membre.idmembre
            INNER JOIN Projet ON contribuer.id_projet = Projet.id_projet
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
                $membres[] = new Membre($donnees);
            }
            return $membres;
        }	

		// retourne l'ensemble des membres 
        public function getList() {
            $membres = array();
			$req = 'SELECT idmembre,nom,prenom, email, anneenaissance, sexe, telportable FROM membre';
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
				$membres[] = new Membre($donnees);
			}
			return $membres;
        }

		// modification du membre connecté
		public function update(Membre $membre) : bool {
			$req = "UPDATE membre SET admin = :admin, "
						. "nom = :nom, "
						. "prenom = :prenom, "
						. "email  = :email, "
						. "password = :password, "
						. "anneenaissance = :anneenaissance, "
						. "sexe= :sexe, "
						. "telportable = :telportable" 
						. " WHERE idmembre = :idmembre";
			//var_dump($membre);
	
			$stmt = $this->_db->prepare($req);
			$stmt->execute(array(":admin" => $membre->admin(),
									":nom" => $membre->nom(),
									":prenom" => $membre->prenom(),
									":email" => $membre->email(),
									":password" => $membre->password(), 
									":anneenaissance" => $membre->anneenaissance(),
									":sexe" => $membre->sexe(),
									":telportable" => $membre->telportable(),
									":idmembre" => $membre->idmembre() ));
			return $stmt->rowCount();
			
		}
		
    }

?>