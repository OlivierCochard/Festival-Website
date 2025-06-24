<?php
require_once("Lib.php");
$action = key_exists('action', $_GET)? trim($_GET['action']): null;
$filtre = key_exists('filtre', $_GET)? trim($_GET['filtre']): null;
$sauvegarde = key_exists('sauvegarde', $_GET)? trim($_GET['sauvegarde']): null;
switch ($action) {

	case "liste":
		$corps="<h1>Liste des festivals</h1>";
		$connection =connecter();

		$requete='SELECT * FROM festival ORDER BY '.$filtre;

		$query  = $connection->query($requete);
		$query->setFetchMode(PDO::FETCH_OBJ);

		$corps.= "<h4><span class='c1'><b><u>ID</u></span>
		<span class='c1'>Nom</span>
		<span class='c1'>Style</span>
		<span class='c1'>Capacite</span>
		<span class='c1'>Action</b></span></h4>";

		while($enregistrement = $query->fetch())
		{
			$id=$enregistrement->id;
			$nom=$enregistrement->nom;
			$style=$enregistrement->style;
			$capacite=$enregistrement->capacite;

			$corps.= "<span class='c1'><u><b>".$id."</b></u></span>
			<span class='c1'>".$nom."</b></u></span>
			<span class='c1'>".$style."</span>
			<span class='c1'>".$capacite."</span>";

			$corps.='<span class=c2><a href=index.php?action=select&id='.$id.'><img src=Images/select.png alt=select.png></a></span>';
			$corps.='<span class=c2><a href=index.php?action=update&id='.$id.'><img src=Images/update.png alt=update.png></a></span>';
			$corps.='<span class=c2><a href=index.php?action=delete&id='.$id.'><img src=Images/trash.png alt=trash.png></a></span>';
			$corps.="<br>";
		}
		$zonePrincipale=$corps ;
		$query = null;
		$connection = null;
		break;

	case "insert":
		$cible='insert';

		if (key_exists("dateDepart", $_POST) == false)
		{
			$_POST["dateDepart"] = "YYYY-MM-DD";
		}
		else if (key_exists('dateDepart', $erreur))
		{
			if ($_POST["dateDepart"] == "")
			{
				$_POST["dateDepart"] = "YYYY-MM-DD";
			}
		}

		if (key_exists("dateFin", $_POST) == false)
		{
			$_POST["dateFin"] = "YYYY-MM-DD";
		}
		else if (key_exists('dateFin', $erreur))
		{
			if ($_POST["dateFin"] == "")
			{
				$_POST["dateFin"] = "YYYY-MM-DD";
			}
		}

		if (!isset($_POST["nom"])	&& !isset($_POST["style"]) && !isset($_POST["capacite"]) && !isset($_POST["dateDepart"]) && !isset($_POST["dateFin"]) && !isset($_POST["adresseBilleterie"]) && !isset($_POST["adresseLieu"]))
		{
			include("formulaireFestival.html");
		}
		else{
			//nom
			$nom = key_exists('nom', $_POST)? trim($_POST['nom']): null;
			if ($nom=="")
			{
				$erreur["nom"] ="champ vide";
			}
			else if (controlerAlphanum($nom) == false)
			{
				$erreur["nom"] ="ce champ contient des caractères interdits";
			}
			else if (controlerNum($nom) == true)
			{
				$erreur["nom"] ="ce champ ne peut pas contenir une serie de chiffres";
			}
			//style
			$style = key_exists('style', $_POST)? trim($_POST['style']): null;
			if ($style=="")
			{
				$erreur["style"] ="champ vide";
			}
			else if (controlerAlphanum($style) == false)
			{
				$erreur["style"] ="ce champ contient des caractères interdits";
			}
			else if (controlerNum($style) == true)
			{
				$erreur["style"] ="ce champ ne peut pas contenir une serie de chiffres";
			}
			//capacite
			$capacite = key_exists('capacite', $_POST)? trim($_POST['capacite']): null;
			if ($capacite=="")
			{
				$erreur["capacite"] ="champ vide";
			}
			else if (controlerNum($style) == true)
			{
				$erreur["capacite"] ="ce champ doit contenir une serie de chiffres";
			}
			//dateDepart
			$dateDepart = key_exists('dateDepart', $_POST)? trim($_POST['dateDepart']): null;
			if ($dateDepart=="")
			{
				$erreur["dateDepart"] ="champ vide";
			}
			//dateFin
			$dateFin = key_exists('dateFin', $_POST)? trim($_POST['dateFin']): null;
			if ($dateFin=="")
			{
				$erreur["dateFin"] ="champ vide";
			}
			//adresseBilleterie
			$adresseBilleterie = key_exists('adresseBilleterie', $_POST)? trim($_POST['adresseBilleterie']): null;
			if ($adresseBilleterie=="")
			{
				$erreur["adresseBilleterie"] ="champ vide";
			}
			//adresseLieu
			$adresseLieu = key_exists('adresseLieu', $_POST)? trim($_POST['adresseLieu']): null;
			if ($adresseLieu=="")
			{
				$erreur["adresseLieu"] ="champ vide";
			}

			//gestion erreurs
			$compteur_erreur=count($erreur);
			foreach ($erreur as $cle=>$valeur){
				if ($valeur==null) $compteur_erreur=$compteur_erreur-1;
			}

			if ($compteur_erreur == 0) {
				$connection =connecter();
				$corps = "Connection etablie <br>";
				$corps .= "Il faut maintenant insérer les données du formulaire dans la base <br>";

				$requete = "SELECT max(id) FROM festival";
				$query  = $connection->query($requete);
				$idP = $query->fetch()[0] + 1;

				$corps .= "et récupérer l'identifiant". strval($idP). "<br>";

				//A compléter
				$data = [
					'id' => $id,
					'nom' => $nom,
					'style' => $style,
					'capacite' => $capacite,
					'dateDepart' => $dateDepart,
					'dateFin' => $dateFin,
					'adresseBilleterie' => $adresseBilleterie,
					'adresseLieu' => $adresseLieu
				];
				$sql = "INSERT INTO festival (id, nom, style, capacite, dateDepart, dateFin, adresseBilleterie, adresseLieu) VALUES
				(:id, :nom, :style, :capacite, :dateDepart, :dateFin, :adresseBilleterie, :adresseLieu)";
				$stmt = $connection->prepare($sql);
				$stmt->execute($data);

				$festival = new Festival($id,$nom,$style,$capacite,$dateDepart,$dateFin,$adresseBilleterie,$adresseLieu);
				$corps .= "Saisie de : ". $festival;

				$zonePrincipale=$corps ;
				$connection = null;
			}
			else {
				include("formulaireFestival.html");
			}
		}
		break;

	case "select":
		$id=$_GET["id"];
		$corps="<h1>Selection d'un festival</h1>";
		$connection =connecter();
		$requete="SELECT * FROM festival WHERE (id = $id)";
		$query  = $connection->query($requete);
		$query->setFetchMode(PDO::FETCH_OBJ);

		while( $enregistrement = $query->fetch() )
		{
			$id=$enregistrement->id;
			$nom=$enregistrement->nom;
			$style=$enregistrement->style;
			$capacite=$enregistrement->capacite;
			$dateDepart=$enregistrement->dateDepart;
			$dateFin=$enregistrement->dateFin;
			$adresseBilleterie=$enregistrement->adresseBilleterie;
			$adresseLieu=$enregistrement->adresseLieu;

			$tab_Festival[$id]=array($nom,$style,$capacite,$dateDepart,$dateFin, $adresseBilleterie, $adresseLieu);
			$corps.= "<p><b>Nom : </b>".$nom."</p>";
			$corps.= "<p><b>Style : </b>".$style."</p>";
			$corps.= "<p><b>Capacité maximum : </b>".$capacite." places</p>";
			$corps.= "<p><b>Date : </b>du (".$dateDepart.") au (".$dateFin.")</p>";
			$corps.= "<p><b>Billeterie : </b>".$adresseBilleterie."</p>";
			$corps.= "<p><b>Adresse : </b>".$adresseLieu."</p>";
		}
		$zonePrincipale=$corps;
		$query = null;
		$connection = null;
		break;

	case "sauvegarde":
		$connection =connecter();
		$type = key_exists('type',$_POST)? $_POST['type']: null;
		$id = key_exists('id',$_POST)? $_POST['id']: null;
		$sql = key_exists('sql',$_POST)? $_POST['sql']: null;
		$requete=$sql;
		$requete=str_replace('.', '', $requete);

		if ($type =='confirmupdate'){
			$corps="<h1>Mise à jour du festival</h1>";
			$corps.="$requete";
		}
		else{
			$corps="<h1>Suppression du festival</h1>";
		}
		$req=$connection->prepare($requete);
		$req->execute();

		$zonePrincipale=$corps;
		$connection = null;
		break;

	case "delete":
		$connection =connecter();
		$id=$_GET["id"];
		$sql="DELETE FROM festival WHERE id = $id";

		$corps = "<h1>Supprimer un festival</h1>";
		$corps.= "<form action='index.php?action=sauvegarde' method='post'>";
		$corps.= "<input type='hidden' name='type' value='confirmdelete'/>";
		$corps.= "<input type='hidden' name='idP' value='.$id.'/>";
		$corps.= "<input type='hidden' name='sql' value='.$sql.'/>";
		$corps.= "<p>Etes vous sûr de vouloir supprimer ce festival ?</p>";
		$corps.= "<p>";
		$corps.= "<input type='submit' value='Enregistrer' class='btn btn-danger'>";
		$corps.= "<a href='index.php' class='btn btn-secondary'>Annuler</a>";
		$corps.= "</p>";
		$corps.= "</form>";

		$zonePrincipale=$corps ;
		$connection = null;
		break;

	case "update":
		$cible='update';
		$connection =connecter();
		$id=$_GET["id"];
		$requete="SELECT * FROM festival WHERE id = $id";

		if (key_exists("nom", $_POST) == false)
		{
			$query  = $connection->query($requete);
			$query->setFetchMode(PDO::FETCH_OBJ);
			$_POST["nom"] = $query->fetch()->nom.' (à modifier)';
		}
		if (key_exists("style", $_POST) == false)
		{
			$query  = $connection->query($requete);
			$query->setFetchMode(PDO::FETCH_OBJ);
			$_POST["style"] = $query->fetch()->style;
		}
		if (key_exists("capacite", $_POST) == false)
		{
			$query  = $connection->query($requete);
			$query->setFetchMode(PDO::FETCH_OBJ);
			$_POST["capacite"] = $query->fetch()->capacite;
		}
		if (key_exists("dateDepart", $_POST) == false)
		{
			$query  = $connection->query($requete);
			$query->setFetchMode(PDO::FETCH_OBJ);
			$_POST["dateDepart"] = $query->fetch()->dateDepart;
		}
		if (key_exists("dateFin", $_POST) == false)
		{
			$query  = $connection->query($requete);
			$query->setFetchMode(PDO::FETCH_OBJ);
			$_POST["dateFin"] = $query->fetch()->dateFin;
		}
		if (key_exists("adresseBilleterie", $_POST) == false)
		{
			$query  = $connection->query($requete);
			$query->setFetchMode(PDO::FETCH_OBJ);
			$_POST["adresseBilleterie"] = $query->fetch()->adresseBilleterie;
		}
		if (key_exists("adresseLieu", $_POST) == false)
		{
			$query  = $connection->query($requete);
			$query->setFetchMode(PDO::FETCH_OBJ);
			$_POST["adresseLieu"] = $query->fetch()->adresseLieu;
		}

		if (!isset($_POST["nom"])	&& !isset($_POST["style"]) && !isset($_POST["capacite"]) && !isset($_POST["dateDepart"]) && !isset($_POST["dateFin"])&& !isset($_POST["adresseBilleterie"]) && !isset($_POST["adresselieu"]))
		{
			include("formulaireFestival.html");
			$erreur["nom"] =" ";
		}
		else{
			//nom
			$nom = key_exists('nom', $_POST)? trim($_POST['nom']): null;
			if ($nom=="")
			{
				$erreur["nom"] ="champ vide";
			}
			else if (controlerAlphanum($nom) == false)
			{
				$erreur["nom"] ="ce champ contient des caractères interdits";
			}
			else if (controlerNum($nom) == true)
			{
				$erreur["nom"] ="ce champ ne peut pas contenir une serie de chiffres";
			}
			//style
			$style = key_exists('style', $_POST)? trim($_POST['style']): null;
			if ($style=="")
			{
				$erreur["style"] ="champ vide";
			}
			else if (controlerAlphanum($style) == false)
			{
				$erreur["style"] ="ce champ contient des caractères interdits";
			}
			else if (controlerNum($style) == true)
			{
				$erreur["style"] ="ce champ ne peut pas contenir une serie de chiffres";
			}
			//capacite
			$capacite = key_exists('capacite', $_POST)? trim($_POST['capacite']): null;
			if ($capacite=="")
			{
				$erreur["capacite"] ="champ vide";
			}
			else if (controlerNum($style) == true)
			{
				$erreur["capacite"] ="ce champ doit contenir une serie de chiffres";
			}
			//dateDepart
			$dateDepart = key_exists('dateDepart', $_POST)? trim($_POST['dateDepart']): null;
			if ($dateDepart=="")
			{
				$erreur["dateDepart"] ="champ vide";
			}
			//dateFin
			$dateFin = key_exists('dateFin', $_POST)? trim($_POST['dateFin']): null;
			if ($dateFin=="")
			{
				$erreur["dateFin"] ="champ vide";
			}
			//adresseBilleterie
			$adresseBilleterie = key_exists('adresseBilleterie', $_POST)? trim($_POST['adresseBilleterie']): null;
			if ($adresseBilleterie=="")
			{
				$erreur["adresseBilleterie"] ="champ vide";
			}
			//adresseLieu
			$adresseLieu = key_exists('adresseLieu', $_POST)? trim($_POST['adresseLieu']): null;
			if ($adresseLieu=="")
			{
				$erreur["adresseLieu"] ="champ vide";
			}

			//gestion erreurs
			$compteur_erreur=count($erreur);
			foreach ($erreur as $cle=>$valeur){
				if ($valeur==null) $compteur_erreur=$compteur_erreur-1;
			}

			if ($compteur_erreur == 0) {
				$connection =connecter();
				$sql="UPDATE festival SET
				nom = ".'"'.$nom.'"'.",
				style = ".'"'.$style.'"'.",
				capacite = ".'"'.$capacite.'"'.",
				dateDepart = ".'"'.$dateDepart.'"'.",
				dateFin = ".'"'.$dateFin.'"'.",
				adresseBilleterie = ".'"'.$adresseBilleterie.'"'.",
				adresseLieu = ".'"'.$adresseLieu.'"'."
				WHERE id = ".$id;

				$corps = "<h1>Modifier le festival</h1>";
				$corps.= "<form action='index.php?action=sauvegarde' method='post'>";
				$corps.= "<input type='hidden' name='type' value='confirmupdate'/>";
				$corps.= "<input type='hidden' name='idP' value='.$id.'/>";
				$corps.= "<input type='hidden' name='sql' value='.$sql.'/>";
				$corps.= "<p>Etes vous sûr de vouloir mettre à jour ce festival ?</p>";
				$corps.= "<p>";
				$corps.= "<input type='submit' value='Enregistrer' class='btn'>";
				$corps.= "<a href='index.php' class='btn'>Annuler</a>";
				$corps.= "</p>";
				$corps.= "</form>";

				$zonePrincipale=$corps ;
				$connection = null;
				break;
			}
			else {
				include("formulaireFestival.html");
			}
		}
		break;

 default:
   $zonePrincipale="" ;
   break;

}
include("squelette.php");

?>
