<?php session_start();

// if nécessaire ? 
if (isset($_POST['modif_dispos'])){
    include_once('connexion_sql.php');

    //mise dans un tableau $nouv_dispo de toutes les infos à modifier/insérer dans la table dates
    // rappel ordre de dates : id_dates, id_profil, date_choix, date_0, date_1 ... date_13 
    $ajd=date('Y-m-d');
    $nouv_dispo['date_choix']=$ajd;
    for ($i=0; $i < 7 ; $i++) {
        // htmlspecialchars nécessaire? (l'entrée est conditionnée par un input type="number")
        $nouv_dispo['date_'.$i] = htmlspecialchars($_POST['date_'.$i]);
    }

    // on vérifie si l'id existe déjà dans la table dates (si a déjà rempli des dispos par le passé)
    $req_existe=$bdd->prepare('SELECT id_profil FROm dates WHERE id_profil=?');
    $req_existe-> execute(array($_SESSION['id_profil']));
    $existe=$req_existe->fetch();

    /* les phrases permettent d'adapter ensuite rapidement le code si l'on veut un affichage différent de 7 jours */
    if (!empty($existe)) {
        // si l'id existe : modifier la ligne correspondante
        $phrase_modif="";
        foreach ($nouv_dispo as $date_dispo => $place_dispo) {
            $phrase_modif=$phrase_modif.",".$date_dispo."=:".$date_dispo;
        }
        $phrase_modif=substr($phrase_modif, 1);
        //on met l'élément id_profil à la fin du tableau pour raison de syntaxe SQL
        $nouv_dispo['id_profil']=$_SESSION['id_profil'];
        $req_modif_dispo=$bdd->prepare('UPDATE dates SET '.$phrase_modif.' WHERE id_profil=:id_profil');
        $req_modif_dispo->execute($nouv_dispo);
    } else {
        // si l'id n'existe pas déjà : insérer une ligne avec toutes les données à initialiser
        // on met l'id_profil en début de tableau pour raison de syntaxe SQL
        $nouv_dispo=array('id_profil'=>$_SESSION['id_profil'])+$nouv_dispo;
        $champs_init="";
        $valeurs_init="";
        foreach ($nouv_dispo as $date_dispo => $place_dispo) {
            $champs_init=$champs_init.",".$date_dispo;
            $valeurs_init=$valeurs_init.",:".$date_dispo;
        }
        $champs_init=substr($champs_init, 1);
        $valeurs_init=substr($valeurs_init, 1);

        $req_insert_dispo=$bdd->prepare('INSERT INTO dates ('.$champs_init.') VALUES ('.$valeurs_init.')');
        $req_insert_dispo->execute($nouv_dispo);
    }
} 
header('Location: /www/hebergement');
exit();
?>