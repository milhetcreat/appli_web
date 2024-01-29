<?php
/** 
* définition de la classe contribuer
*/
class Contribuer {
        private string $_idmembre;
        private int $_id_projet;

        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['idmembre'])) { $this->_idmembre = $donnees['idmembre']; }
			if (isset($donnees['id_projet'])) { $this->_id_projet = $donnees['id_projet']; }
        }       

        // GETTERS //
		public function idmembre() { return $this->_idmembre;}
		public function id_projet() { return $this->_id_projet;}
		
		// SETTERS //
        public function setidmembre(string $idmembre) { $this->_idmembre= $idmembre; }
		public function setIdTag(int $idtag) { $this->_id_projet = $idtag; }

    }

?>