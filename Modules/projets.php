<?php
/** 
* définition de la classe itineraire
*/
class Projets {
        private int $_id_projet;
        private string $_titre;
        private string $_description;
		private string $_img;
		private string $_lien_demo ;
		private string $_lien_source;
		private int $_id_cours;
		private int $_valide;
		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_projet'])) { $this->_id_projet = $donnees['id_projet']; }
			if (isset($donnees['titre'])) { $this->_titre = $donnees['titre']; }
			if (isset($donnees['description'])) { $this->_description = $donnees['description']; }
			if (isset($donnees['img'])) { $this->_img = $donnees['img']; }
			if (isset($donnees['lien_demo'])) { $this->_lien_demo = $donnees['lien_demo']; }
			if (isset($donnees['lien_source'])) { $this->_lien_source = $donnees['lien_source']; }
			if (isset($donnees['id_cours'])) { $this->_id_cours = $donnees['id_cours']; }
			if (isset($donnees['valide'])) { $this->_valide = $donnees['valide']; }
        }           
        // GETTERS //
		public function id_projet() { return $this->_id_projet;}
		public function titre() { return $this->_titre;}
		public function description() { return $this->_description;}
		public function img() { return $this->_img;}
		public function lien_demo() { return $this->_lien_demo;}
		public function lien_source() { return $this->_lien_source;}
		public function id_cours() { return $this->_id_cours;}
		public function valide() { return $this->_valide;}
		
		// SETTERS //
		public function setIdProjet(int $idprojet) { $this->_id_projet = $idprojet; }
        public function setTitre(string $titre) { $this->_titre= $titre; }
		public function setDescription(string $description) { $this->_description = $description; }
		public function setImg(string $img) { $this->_img = $img; }
		public function setLienDemo(string $liendemo) { $this->_lien_demo = $liendemo; }
		public function setLienSource(int $liensource) { $this->_lien_source = $liensource; }	
		public function setIdCours(int $idcours) { $this->_id_cours = $idcours; }
		public function setValide(int $valide) { $this->_valide = $valide; }

    }

?>