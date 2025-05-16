<?php
class BDD {

	private $mysqli;
	

	public function __construct() {
		$this -> mysqli = false;
	}

	/* Connexion à la base de données */
	public function connexion() {
		mysqli_report(MYSQLI_REPORT_OFF);
		
		$this -> mysqli = new mysqli('172.16.119.3', 'operateur', 'operateur', 'Quizz');

		if($this -> mysqli -> connect_errno != 0) {
			return false;
		}
		else return true;
	}
	
	
	/* Déconnexion à la base de données */
	public function deconnexion() {
		if($this -> mysqli -> connect_errno != 0) {
			$this -> mysqli -> close();
		}
	}
	
	/* Récupération de la liste des question */
	public function getQuestion($question_id) {
		$question = null;	//Servira a stocker la question

		/* On crée la requete SQL et on lie les paramètres */
		$requete = $this -> mysqli-> prepare("SELECT Id_Question,  Intitule_Question, Multiple FROM Question WHERE Id_Question=?");
		if (!$requete) {
    		return $question;
		}
		else{
			$requete -> bind_param('i', $question_id);
		
			/* On execute la requete et on récupère le résultat */
			$requete -> execute();
			$resultat = $requete -> get_result();
		
			/* On libère la requête */
			$requete -> close();
			
			/* On parcours les résultats pour les stocker */
			if($enregistrement = $resultat -> fetch_object()) {
				$question = $enregistrement;	//On ajoute un element avec un l'id et l'intitule à la suite de nos réponses
			}	
		}
		
		return $question;		//On retourne les réponses de la question
	}
	
	
	/* Récupération des réponses d'une question en utilisant l'id de la question */
	public function getReponses($question_id) {
		$reponses = [];	//Servira a stocker la liste des reponses

		/* On crée la requete SQL et on lie les paramètres */
		$requete = $this -> mysqli-> prepare("SELECT Reponse.Id_Reponse, Reponse.Intitule_Reponse FROM Reponse WHERE Id_Question=?");
		$requete -> bind_param('i', $question_id);
		
		/* On execute la requete et on récupère le résultat */
		$requete -> execute();
		$resultat = $requete -> get_result();
		
		/* On libère la requête */
		$requete -> close();
		
		
		/* On parcours les résultats pour les stocker */
		while ($enregistrement = $resultat -> fetch_object()) {
			$reponses[] = $enregistrement;	//On ajoute un element avec un l'id et l'intitule à la suite de nos réponses
		}
		
	
		return $reponses;		//On retourne les réponses de la question
	}

	public function getMulti($id)
	{
		$multi = [];
		/* On crée la requete SQL et on lie les paramètres */
		$requete = $this -> mysqli-> prepare("SELECT Multiple FROM Question WHERE Id_Question=?");
		$requete -> bind_param('i', $id);
			
		/* On execute la requete et on récupère le résultat */
		$requete -> execute();
		$resultat = $requete -> get_result();
			
		/* On libère la requête */
		$requete -> close();

		while ($enregistrement = $resultat -> fetch_object()) {
			$multi[] = $enregistrement;	//On ajoute un element avec un l'id et l'intitule à la suite de nos réponses
		}
		
	
		return $multi;		//On retourne les réponses de la question
	}

	public function getBonnereponses($id_question)
	{
		$reponses = [];
		/* On crée la requete SQL et on lie les paramètres */
		$requete = $this -> mysqli-> prepare("SELECT Id_Reponse FROM Reponse WHERE Id_Question = ? AND Bonne_Reponse = 1");
		$requete -> bind_param('i', $id_question);
			
		/* On execute la requete et on récupère le résultat */
		$requete -> execute();
		$resultat = $requete -> get_result();
			
		/* On libère la requête */
		$requete -> close();

		while ($enregistrement = $resultat -> fetch_object()) {
			$reponses[] = $enregistrement;	//On ajoute un element avec un l'id et l'intitule à la suite de nos réponses
		}
		
	
		return $reponses;		//On retourne les réponses de la question
	}

	public function getQuizzCategorie(){
		$reponses = [];
		/* On crée la requete SQL et on lie les paramètres */
		$requete = $this -> mysqli-> prepare("SELECT Nom from Quizz");

		$requete -> execute();
		$resultat = $requete -> get_result();
			
		/* On libère la requête */
		$requete -> close();

		while ($enregistrement = $resultat -> fetch_object()) {
			$reponses[] = $enregistrement;	//On ajoute un element avec un l'id et l'intitule à la suite de nos réponses
		}
		
	
		return $reponses;
	}


}
	
?>