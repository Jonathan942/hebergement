<?php 
//si la date de fin (date_debut + nb_jours) est passée, la ligne est déjà supprimée via une requete de nettoyage (cf informations_reseau.php)
$req_dispos=$bdd->prepare('SELECT *, DATEDIFF(CURRENT_DATE(),date_debut) as intervalle FROM jonction_profil_dispo WHERE id_profil=? ORDER BY date_debut');
$req_dispos->execute(array($_SESSION['id_profil']));
$dispos=array();
while($reponse_dispos=$req_dispos->fetch()){
	//on met dans un tableau: date_debut, date_fin, nb_jours, nb_places
	$date_fin=date('d/m', strtotime($reponse_dispos['date_debut'].'+'.$reponse_dispos['nb_jours'].' DAY'));
	//si la date de début est déjà passée, a l'affichage on ne mettra qu'à partir de ajd
	if ($reponse_dispos['intervalle']>0) {
		$date_debut=date('d/m');
		$nb_jours=$reponse_dispos['nb_jours']-$reponse_dispos['intervalle'];
	} else {
		$date_debut=date('d/m', strtotime($reponse_dispos['date_debut']));
		$nb_jours=$reponse_dispos['nb_jours'];
	}
	$dispos[$reponse_dispos['id_jonction_pd']]=array('date_debut'=>$date_debut,'date_fin'=>$date_fin,'nb_jours'=>$nb_jours,'nb_places'=>$reponse_dispos['nb_places']);
}

?>