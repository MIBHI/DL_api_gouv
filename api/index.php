<?php

    require_once'class/DataUtil.php';

   // DataUtil::dailyUpdate();

    $res =  array();

    error();

    if (file_exists('data/'.DataUtil::getFileName())) {


        //Store into var array of object

        $xml = simplexml_load_file("data/".DataUtil::getFileName());

        //While pdv
        $data = array();

        foreach ($xml as $pdv) {

            //Get data match $cp
            if (isset($_GET['cp']) && strval($pdv->attributes()->cp) == $_GET['cp']) {

                //init. price
                $price = 0;

                //if <prix> existe into xml file
                if (isset($pdv->prix)) {

                    //foreach all price
                    foreach ($pdv->prix as $value) {

                        //Get value filter url get
                        if (isset($_GET['type']) && 0 == strcasecmp($_GET['type'], $value['nom'])) {

                            $price = $value['valeur']/1000;

                        }
                    }

                }

                //Store data in associated array

                if (floatval($price) > 0) {

                    $data[] = [

                        'addr'  => strval($pdv->adresse),
                        //'cp'=>$pdv->attributes()->cp,
                        //'ville'=>$pdv->ville,
                        //'ouverture'=>$pdv->ouverture->attributes()->debut,
                        //'fermeture'=>$pdv->ouverture->attributes()->fin,
                        'price' => floatval($price)];
                }
            }

        }

        if (!isset($_GET["sort"]) || strtoupper($_GET["sort"]) == "ASC" ) {

            usort($data, "cmpAsc");
            $res["status"]  = "ok";
            $res["results"] = $data;


        } else if (strtoupper($_GET["sort"]) == "DESC") {

            usort($data, "cmpDesc");
            $res["status"]  = "ok";
            $res["results"] = $data;

        }

        echo json_encode($res, JSON_PRETTY_PRINT);
        exit();


    }

    // functions


    // usort ascending
    function cmpAsc($a, $b)  {

        if ($a['price'] == $b['price']) {
            return 0;
        }

        return ($a['price'] < $b['price']) ? -1 : 1;
    }

    // usort descending
    function cmpDesc($a, $b)  {

        if ($a['price'] == $b['price']) {
            return 0;
        }

        return ($a['price'] > $b['price']) ? -1 : 1;
    }

     // management error
     function error() {

         // error Global
         $globalErr["error_message"] = "The cp and the type are not define";

         // error sort
         $sortErr["error_message"] = "The sort must be ASC or DESC";

         // error cp
         $cpErr["error_message"] = "The cp is not define";

         // error type
         $type = array();
         $type = [
             "Gazole",
             "E10",
             "E85",
             "SP95",
             "SP98",
             "GPL",
         ];
         $typeErrUnk["error_message"] = "The type must mach with the xml file";
         $typeErr["error_message"]    = "The type is note define";

         // error result and status
         $err["results"] = array();
         $err["status"]  = "INVALID_PARAM";

         if ((!isset($_GET['cp']) || strlen($_GET['cp'] == 0 || intval($_GET['cp'])<=0))
             && (!isset($_GET['type']) || strlen($_GET['type']) == 0)) {

             $theErr = array_merge($globalErr, $err);
             $typeErr = json_encode($theErr, JSON_PRETTY_PRINT);
             echo $typeErr;
             exit();
         }


         if(!isset($_GET['cp']) || strlen($_GET['cp'] == 0 || intval($_GET['cp'])<=0)) {

             $theErr = array_merge($cpErr, $err);
             $cpErr = json_encode($theErr, JSON_PRETTY_PRINT);
             echo $cpErr;
             exit();
         }

         if (!isset($_GET['type']) || strlen($_GET['type']) == 0 ) {

             $theErr = array_merge($typeErr, $err);
             $typeErr = json_encode($theErr, JSON_PRETTY_PRINT);
             echo $typeErr;
             exit();
         }

         $found = false;
         foreach ($type as $line) {

             if (0 == strcasecmp($_GET['type'], $line)) {

                 $found = true ;
                 break;
             }
         }

         if (!$found) {

             $theErrUnk = array_merge($typeErrUnk, $err);
             $typeErr   = json_encode($theErrUnk, JSON_PRETTY_PRINT);
             echo $typeErr ;
             exit();
         }

         if (isset($_GET['sort']) && strtoupper(strval($_GET['sort'])!="ASC") && strtoupper(strval($_GET['sort']))!="DESC")  {

             $theErr = array_merge($sortErr, $err);
             $sortErr = json_encode($theErr, JSON_PRETTY_PRINT);
             echo $sortErr;
             exit();
         }
     }
