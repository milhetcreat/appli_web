<?php
/** 
* définition de la classe commentaire
*/
class Commentaire {
        private int $_id_com;
        private string $_description;
        private string $_idmembre;
        private int $_id_projet;
        
		private string $_nom;
        private string $_prenom;


        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_com'])) { $this->_id_com = $donnees['id_com']; }
			if (isset($donnees['description'])) { $this->_description = $donnees['description']; }
			if (isset($donnees['idmembre'])) { $this->_idmembre = $donnees['idmembre']; }
			if (isset($donnees['id_projet'])) { $this->_id_projet = $donnees['id_projet']; }

			if (isset($donnees['nom'])) { $this->_nom = $donnees['nom']; }
			if (isset($donnees['prenom'])) { $this->_prenom = $donnees['prenom']; }
        }       

        // GETTERS //
		public function description() { return $this->_description;}
		public function id_com() { return $this->_id_com;}
		public function idMembre() { return $this->_idmembre;}
		public function id_projet() { return $this->_id_projet;}

		public function nom() { return $this->_nom;}
		public function prenom() { return $this->_prenom;}

		
		// SETTERS //
        public function setdescription(string $description) { $this->_description= $description; }
		public function setIdCom(int $idcom) { $this->_id_com = $idcom; }
		public function setIdMembre($idmembre) { $this->_idmembre = $idmembre; }
		public function setIdProjet(int $idprojet) { $this->_id_projet = $idprojet; }

    }

?>