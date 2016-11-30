<?php

$cp = 83300;

if (file_exists('data/PrixCarburants_quotidien_20161123.xml')) {

    $xml = simplexml_load_file('data/PrixCarburants_quotidien_20161123.xml');
    echo '<pre>';
    //print_r($xml);
    echo '</pre>';


    foreach( $xml as $pdv){
        if($pdv->attributes()->cp == $cp){
            echo $pdv->adresse . '<br>';
            echo $pdv->attributes()->cp . ' ' . $pdv->ville ."<br>";
        }
    }

} else {
    exit('Echec lors de l\'ouverture du fichier xml.');
}

