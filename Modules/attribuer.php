<?php
/** 
* définition de la classe attribuer
*/
class Attribuer {
        private int $_id_projet;
        private int $_num_cat;

        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_projet'])) { $this->_id_projet = $donnees['id_projet']; }
			if (isset($donnees['num_cat'])) { $this->_num_cat = $donnees['num_cat']; }
        }       

        // GETTERS //
		public function id_projet() { return $this->_id_projet;}
		public function num_cat() { return $this->_num_cat;}
		
		// SETTERS //
        public function setid_projet(string $id_projet) { $this->_id_projet= $id_projet; }
		public function setIdTag(int $idtag) { $this->_num_cat = $idtag; }

    }

?>