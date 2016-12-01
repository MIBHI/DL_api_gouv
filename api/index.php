<?php

    require_once'class/DataUtil.php';

    DataUtil::dailyUpdate();

    $err["error_message"] = "Invalid parameter";
    $err["results"] = array();
    $err["status"]  = "INVALID_PARAM";
    $err = json_encode($err, JSON_PRETTY_PRINT);

    if (file_exists('data/'.DataUtil::getFileName())) {

        //Store into var array of object
        $xml = simplexml_load_file('data/PrixCarburants_quotidien_20161123.xml');

        //While pdv
        $data = array();
        foreach ( $xml as $pdv) {

            //Get data match $cp
            if ($pdv->attributes()->cp == $_GET['cp']) {

                //init. price
                $price = 0;
                //if <prix> existe into xml file
                if ($pdv->prix) {
                    //foreach all price
                    foreach ($pdv->prix as $value) {

                        //Get value filter url get
                        if ($value['nom'] == ucfirst(strtolower($_GET['type']))) {
                            $price = $value['valeur']/1000;
                        }
                    }

                } else {
                    // TODO:  a verifier
                    //echo $err;
                    //exit(0);
                    continue;
                }

                //Store data in associated array
                /*var_dump($pdv->adresse);
                die();
                */
                $data[] = array(

                    'addr'=>strval($pdv->adresse),
                    //'cp'=>$pdv->attributes()->cp,
                    //                    'ville'=>$pdv->ville,
                    //                    'ouverture'=>$pdv->ouverture->attributes()->debut,
                    //                    'fermeture'=>$pdv->ouverture->attributes()->fin,
                    'price'=>floatval($price)
                );


            } else {

                //                echo $err;
                //                exit(0);
            }
        }
        //usort($data, "cmpAsc");
        usort($data, "cmpDesc");
        $res["result"] = $data;
        $res["status"]  = "ok";

        echo json_encode($res, JSON_PRETTY_PRINT);


    } else {

        echo $err;
        exit(0);
    }


    // usort
    function cmpAsc($a, $b)  {
        if ($a['price'] == $b['price']) {
            return 0;
        }
        return ($a['price'] < $b['price']) ? -1 : 1;
    }

    function cmpDesc($a, $b)  {
        if ($a['price'] == $b['price']) {
            return 0;
        }
        return ($a['price'] > $b['price']) ? -1 : 1;
    }
