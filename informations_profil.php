<?php
include_once('connexion_sql.php');

// récupération des informations concernant le profil 
$recup_infos=$bdd->prepare('SELECT * FROM profil AS p WHERE id_profil=?'); 
$recup_infos->execute(array($_SESSION['id_profil'])); 
$infos=$recup_infos->fetch();

// récupération des organisations auxquelles appartient le profil. 
unset($_SESSION['orgas_appartenance']);
$recup_orgas_appartenance=$bdd->prepare('SELECT j.id_orga, o.nom_orga FROM jonction_profil_organisation AS j JOIN organisation AS o ON j.id_orga=o.id_orga WHERE j.id_profil=?');
$recup_orgas_appartenance->execute(array($_SESSION['id_profil']));
while ($reponse_orgas_appartenance=$recup_orgas_appartenance->fetch()){
    $_SESSION['orgas_appartenance'][$reponse_orgas_appartenance['id_orga']]=$reponse_orgas_appartenance['nom_orga'];
}

// récupération de toutes les organisations et mise dans un tableau qui servira pour la liste
unset($_SESSION['all_orgas']);
$recup_all_orgas=$bdd->query('SELECT nom_orga FROM organisation'); 
while ($reponse_all_orgas = $recup_all_orgas->fetch()) {
    $_SESSION['all_orgas'][]= $reponse_all_orgas['nom_orga'];
}
?>