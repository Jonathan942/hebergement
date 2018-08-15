<?php 

// récupération des dates déjà signalées comme disponibles pour héberger
//si la date de fin (date_debut + nb_nuits) est passée, la ligne est déjà supprimée via une requete de nettoyage (cf informations_reseau.php)
$req_dispos=$bdd->prepare('SELECT *, DATEDIFF(CURRENT_DATE(),date_debut) as intervalle FROM dispos_hebergement WHERE id_profil=? ORDER BY date_debut');
$req_dispos->execute(array($_SESSION['id_profil']));
$dispos_hebergement=array();
while($reponse_dispos=$req_dispos->fetch()){
	//on met dans un tableau: date_debut, date_fin, nb_nuits, nb_places
	$date_fin=date('d/m', strtotime($reponse_dispos['date_debut'].'+'.$reponse_dispos['nb_nuits'].' DAY'));
	//si la date de début est déjà passée, a l'affichage on ne mettra qu'à partir de ajd
	if ($reponse_dispos['intervalle']>0) {
		$date_debut=date('d/m');
		$nb_nuits=$reponse_dispos['nb_nuits']-$reponse_dispos['intervalle'];
	} else {
		$date_debut=date('d/m', strtotime($reponse_dispos['date_debut']));
		$nb_nuits=$reponse_dispos['nb_nuits'];
	}
	$dispos_hebergement[$reponse_dispos['id_dispos']]=array('date_debut'=>$date_debut,'date_fin'=>$date_fin,'nb_nuits'=>$nb_nuits,'nb_places'=>$reponse_dispos['nb_places']);
}

// récupération des infos concernant la description du couchage et la préférence pour l'hébergement
$infos_hebergement=array();
$req_infos_hbgt=$bdd->prepare('SELECT description, preference FROM infos_hebergement WHERE id_profil=?');
$req_infos_hbgt->execute(array($_SESSION['id_profil']));
$infos_hebergement=$req_infos_hbgt->fetch();

?>