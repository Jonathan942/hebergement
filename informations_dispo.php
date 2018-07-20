<?php 
include_once('connexion_sql.php');

// ordre de la table dates : id_dates, id_profil, date_choix, date_0, date_1 ... date_13
// date_choix correspond la date à laquelle la personne à choisi/modifié ses disponibilités
$recup_dates=$bdd->prepare('SELECT *, DATEDIFF(CURRENT_DATE(),date_choix) as intervalle FROM dates WHERE id_profil=?');
$recup_dates->execute(array($_SESSION['id_profil']));
$dates=$recup_dates->fetch();

// récupération des dispos sur les 7 prochains jours
/* rem: créer une fonction affichage_dispo? car ce code revient dans information_reseau.php, et aussi si l'on souhaite paramétrer le nombre de jours affichés (ou simplement afficher les 7 jours suivants, car la bdd stocke les dispos sur 14 jours, mais ça pourrait être plus) */
$date_dispo=date('d-m-Y');
for ($i=0; $i < 7 ; $i++) {
	//recherche du date_x correspond au jour même (prise en compte de l'intervalle de jour s'étant écoulé depuis date_choix)
	$num=$i+$dates['intervalle'];
	if (isset($dates['date_'.$num]) AND is_numeric($dates['date_'.$num])) {
		$place_dispo=$dates['date_'.$num];
	} else {
		$place_dispo=" ";
	}
	// mise en forme de la date pour affichage
	$date_split=date_parse($date_dispo);
	$date_affichee=$date_split['day']."/".$date_split['month'];
	// mise dans un tableau de la date en clef et des dispos en valeur
	$dispos[$date_affichee] = $place_dispo;
    $date_dispo=date('d-m-Y', strtotime($date_dispo.' + 1 DAY'));
} 

?>