<?php

//fonctions utiles
function connecter()
{
    try {
        /*
        MYSQL LOGS
        $dns = "?";
        $utilisateur = "?";
        $motDePasse = "?";
        */

        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                        );
        $connection = new PDO( $dns, $utilisateur, $motDePasse, $options );
        return($connection);

    } catch ( Exception $e ) {
        echo "Connection à MySQL impossible : ", $e->getMessage();
        die();
    }
}

class Festival
{
    private $id;
    private $nom;
    private $style;
    private $capacite;
    private $dateDepart;
    private $dateFin;
    private $adresseBilleterie;
    private $adresseLieu;

    //Constructeur
    public function __construct($id,$nom,$style,$capacite,$dateDepart,$dateFin,$adresseBilleterie,$adresseLieu)
    {
        $this->id=$id;
        $this->nom=$nom;
        $this->style=$style;
        $this->capacite=$capacite;
        $this->dateDepart=$dateDepart;
        $this->dateFin=$dateFin;
        $this->adresseBilleterie=$adresseBilleterie;
        $this->adresseLieu=$adresseLieu;
    }

    //String
    public function __toString()
    {
        $res= "(<u><b>".$this->id."</b></u>, "
        .$this->nom.", "
        .$this->style.", "
        .$this->capacite.", "
        .$this->dateDepart.", "
        .$this->dateFin.", "
        .$this->adresseBilleterie.", "
        .$this->adresseLieu.")<br>";
        return $res;
    }
}

function controlerAlphanum($valeur) {
    if (preg_match("/^[\w|\d|\s|'|\"|\\|,|\.|\-|&|#|;]+$/", $valeur)) return true;
    else return false;
}
function controlerNum($valeur, $strict=false) {
    if ($strict) {
        if (ereg("^[0-9]+$", $valeur)) return true;
        else return false;
    }
    else if (preg_match("/^[\d|\s|\-|\+|E|e|,|\.]+$/", $valeur)) return true;
    else return false;
}

$id=null;$nom = null;$style = null;$capacite = null;$dateDepart =  null;$dateFin = null;$adresseBilleterie = null;$adresseLieu = null;
$erreur=array("nom"=>null,"style"=>null,"capacite"=>null,"dateDepart"=>null,"dateFin"=>null,"adresseBilleterie"=>null,"adresseLieu"=>null);
$tab_Festival=array();
?>
