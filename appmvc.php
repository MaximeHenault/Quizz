<?php

	include("bdd.php");
	include("template.php");

	class AppMVC {

		private $bdd;

		public function __construct() {
			$this -> bdd = new BDD();
		}

		public function afficherPage($mapage, $question, $compt, $categorie) {
			if(!$this -> bdd -> connexion()) {//Connexion à la BDD
				echo "Une erreur est survenue à la connexion";
				return;
			}
			
			if($mapage == 1) $this -> page1();
			else if($mapage == 2) $this -> page2($question, $compt, $categorie);
			else if($mapage == 3) $this -> page3($question);
			else $this -> page1();
			
			$this -> bdd -> deconnexion();
		}

		public function page1(){
			$vue = new Template('template/acceuil.html');

			$quizz_cat = $this -> bdd -> getQuizzCategorie();

			$compt = 1;
			foreach ($quizz_cat as $cat) {
				$vue -> remplacer('#Categorie' . ($compt) . '#', $cat -> Nom);
				$compt += 1;
			}

			echo $vue -> getSortie();
		}
		
		public function page2($id_question, $compteur, $categorie) {
			if ($this -> bdd -> getQuestion2($id_question, $categorie) == null){
				$this -> page3("sur l'histoire", $compteur);
				exit;
			}
			$vue = new Template('template/question.html');	//On récupère le fichier template
			
			$multi = $this -> bdd -> getMulti($id_question);
			foreach($multi as $rep)
			{
				if ($rep -> Multiple == 1)
				{
					$vue -> remplacer('#TYPE_REPONSE#', 'checkbox');
				}
				else{
					$vue -> remplacer('#TYPE_REPONSE#', 'radio');
				}
			}

			$question = $this -> bdd -> getQuestion2($id_question, $categorie);		//On récupère les données de la question
			$reponses = $this -> bdd -> getReponses($id_question);		//On récupère les réponses de la question
			
				//On insère le titre de la question à la place de #TITRE_QUESTION#
			$vue -> remplacer('#QUESTION#', ($id_question + 1));
			$vue -> remplacer('#TITRE_QUESTION#', $question -> Intitule_Question);
			

			$compt = 0;
			foreach ($reponses as $reponse) {
				$vue -> remplacer('#ID' . ($compt+1) . '#', $reponse -> Id_Reponse);	//On insère le titre de la question à la place de #TITRE_QUESTION#
				$vue -> remplacer('#Reponse' . ($compt+1) . '#', $reponse -> Intitule_Reponse);
				$compt += 1;
			}

				//Affichage de la sortie

			if ($_SERVER["REQUEST_METHOD"] == "POST") 
			{
				$rep = $_POST["reponse"];
				$bonnereponses = $this -> bdd -> getBonnereponses($id_question);
				foreach ($bonnereponses as $bonnereponse)
				if ($rep == $bonnereponse -> Id_Reponse)
				{
					$resultat = "<h2 id='green'>Bonne réponse</h2>";
					$compteur += 1;
				}
				else{
					$resultat = "<h2 id='red'>Mauvaise réponse</h2>";
				}
				
				$vue->remplacer('#RESULTAT#', $resultat);
				$vue->remplacer('#BONNEREPONSE#', $compteur);
				$vue->remplacer('#COMPT#', $compteur);
			}
			else {
				$vue->remplacer('#RESULTAT#', '');
				$vue->remplacer('#BONNEREPONSE#', $compteur);
				$vue->remplacer('#COMPT#', $compteur);
			}

			echo $vue -> getSortie();
		}
		
		public function page3($categorie, $compteur) {	
			$vue = new Template('template/finquizz.html');	
			
			$vue->remplacer('#QUIZZ#', $categorie);
			$vue->remplacer('#SCORE#', $compteur);

			echo $vue -> getSortie();
		}

	}
?>