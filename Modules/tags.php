<?php
/** 
* définition de la classe tag
*/
class Tag {
        private int $_id_tag;
        private string $_intitule;

        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['intitule'])) { $this->_intitule = $donnees['intitule']; }
			if (isset($donnees['id_tag'])) { $this->_id_tag = $donnees['id_tag']; }
        }       

        // GETTERS //
		public function intitule() { return $this->_intitule;}
		public function id_tag() { return $this->_id_tag;}
		
		// SETTERS //
        public function setintitule(string $intitule) { $this->_intitule= $intitule; }
		public function setIdTag(int $idtag) { $this->_id_tag = $idtag; }

    }

?>