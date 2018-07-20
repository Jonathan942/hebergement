<?php 

// ordre de la table disponibilite : id_dispo, id_profil, date_choix, date_0, date_1 ... date_13
// date_choix correspond la date à laquelle la personne à choisi/modifié ses disponibilités
$recup_dispos=$bdd->prepare('SELECT *, DATEDIFF(CURRENT_DATE(),date_choix) as intervalle FROM disponibilite WHERE id_profil=?');
$recup_dispos->execute(array($_SESSION['id_profil']));
$dispos=$recup_dispos->fetch();

// récupération des dispos sur les 7 prochains jours
/* rem: créer une fonction affichage_dispo? car ce code revient dans information_reseau.php, et aussi si l'on souhaite paramétrer le nombre de jours affichés (ou simplement afficher les 7 jours suivants, car la bdd stocke les dispos sur 14 jours, mais ça pourrait être plus) */
$date_dispo=date('d-m-Y');
for ($i=0; $i < 7 ; $i++) {
	//recherche du date_x correspond au jour même (prise en compte de l'intervalle de jour s'étant écoulé depuis date_choix)
	$num=$i+$dispos['intervalle'];
	if (isset($dispos['date_'.$num]) AND is_numeric($dispos['date_'.$num])) {
		$place_dispo=$dispos['date_'.$num];
	} else {
		$place_dispo=" ";
	}
	// mise en forme de la date pour affichage
	$date_split=date_parse($date_dispo);
	$date_affichee=$date_split['day']."/".$date_split['month'];
	// mise dans un tableau de la date en clef et des dispos en valeur
	$mes_dispos[$date_affichee] = $place_dispo;
    $date_dispo=date('d-m-Y', strtotime($date_dispo.' + 1 DAY'));
} 

?>