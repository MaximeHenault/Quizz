<?php
class Template {

	private $sortie;
	

	public function __construct($fichier) {
		$this -> sortie = file_get_contents($fichier);
	}
	
	/* Remplacement des variables dans le template */
	public function remplacer($emplacement, $valeur) {
		$this -> sortie = str_replace($emplacement, $valeur, $this -> sortie);
	}

	/* Récupération de la sortie */
	public function getSortie() {return $this -> sortie;}
}
?>