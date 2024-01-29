<?php
/** 
* définition de la classe realiser
*/
class Realiser {
        private int $_id_projet;
        private int $_id_tag;

        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_projet'])) { $this->_id_projet = $donnees['id_projet']; }
			if (isset($donnees['id_tag'])) { $this->_id_tag = $donnees['id_tag']; }
        }       

        // GETTERS //
		public function id_projet() { return $this->_id_projet;}
		public function id_tag() { return $this->_id_tag;}
		
		// SETTERS //
        public function setid_projet(string $id_projet) { $this->_id_projet= $id_projet; }
		public function setIdTag(int $idtag) { $this->_id_tag = $idtag; }

    }

?>