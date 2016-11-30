<?php

require_once 'class/DataUtil.php';

//Method update daily data
DataUtil::dailyUpdate();


if (file_exists('data/'.DataUtil::getFileName())) {

    //Store into var array of object
    $xml = simplexml_load_file('data/'.DataUtil::getFileName());

    //While pdv
    foreach( $xml as $pdv){

        //Get data match $cp
        if($pdv->attributes()->cp == $_GET['cp']){

            //init. price
            $price = 0;

            if($pdv->prix){
                //foreach all price
                foreach($pdv->prix as $value){
                    //Get value filter url get
                    if($value['nom'] == $_GET['type']){
                        $price = $value['valeur']/1000;
                    }
                }
            }

            //Store data in array
            $data = array(
                'adress'=>$pdv->adresse,
                'cb'=>$pdv->attributes()->cp,
                'ville'=>$pdv->ville,
                'ouverture'=>$pdv->ouverture->attributes()->debut,
                'fermeture'=>$pdv->ouverture->attributes()->fin,
                'prix'=>$price
            );

            echo json_encode($data);
        }
    }

} else {
    exit('Echec lors de l\'ouverture du fichier xml.');
}

