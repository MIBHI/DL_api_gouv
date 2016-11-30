<?php

    if (file_exists('data/PrixCarburants_quotidien_20161123.xml')) {
        //Store into var array of object
        $xml = simplexml_load_file('data/PrixCarburants_quotidien_20161123.xml');

        //While pdv
        foreach( $xml as $pdv) {

            //Get data match $cp
            if($pdv->attributes()->cp == $_GET['cp']) {

                //init. price
                $price = 0;

                //foreach all price
                foreach($pdv->prix as $value){
                    //Get value filter url get
                    if($value['nom'] == $_GET['type']){
                        $price = $value['valeur']/1000;

                    }
                }


                //if <prix> exist into xml file
                if($pdv->prix){
                    //Get price match $type
                    if(0==strcasecmp($pdv->prix->attributes()->nom ,$_GET['type'])){
                        //Store value into $price
                        $price =  floatval($pdv->prix->attributes()->valeur/1000);
                    }
                }

                //Store data in array
                echo "<pre>";
                $data = array(
                    'adress'=>$pdv->adresse,
                    'cp'=>$pdv->attributes()->cp,
//                    'ville'=>$pdv->ville,
//                    'ouverture'=>$pdv->ouverture->attributes()->debut,
//                    'fermeture'=>$pdv->ouverture->attributes()->fin,
                    'prix'=>$price

                );

                echo json_encode($data, JSON_PRETTY_PRINT)."<br>";
            }
        }

    } else {

        $err["error_message"] = "Invalid parameter";
        $err["results"] = array();
        $err["status"]  = "INVALID_PARAM";
        echo json_encode($err, JSON_PRETTY_PRINT);
    }

