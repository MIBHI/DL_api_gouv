<?php

/**
 * Created by PhpStorm.
 * User: resine
 * Date: 30/11/2016
 * Time: 13:45
 */
class DataUtil {

    static function getFileName(){
        //Open dir
        if ($handle = opendir('data/')) {

            //Loop over the directory
            while (false !== ($entry = readdir($handle))) {

                //if file is xml
                if(pathinfo($entry)['extension'] == 'xml'){
                    $fileName = $entry;
                }
            }

            //Close dir
            closedir($handle);
        }

        return $fileName;
    }

    static function cleanData(){
        //Open dir
        if ($handle = opendir('data/')) {

            //Loop over the directory
            while (false !== ($entry = readdir($handle))) {

                //if file is xml
                if(pathinfo($entry)['extension'] == 'xml'){
                    //Delete file
                    unlink('data/'.$entry);
                }
            }

            //Close dir
            closedir($handle);
        }

        return true;
    }

    static function dailyUpdate(){

        //Clean dir before update
        static::cleanData();

        //Url opendata
        $url = 'http://donnees.roulez-eco.fr/opendata/jour';

        //Get zip file and store into data dir temporary
        file_put_contents('data/temp.zip', fopen($url, 'r'));

        //Unzip file and store into data dir
        $zip = new ZipArchive;
        if ($zip->open('data/temp.zip') === TRUE) {
            $zip->extractTo('data/');
            $zip->close();
            echo 'ok';
        } else {
            echo 'échec';
        }
        //Delete temp file
        unlink('data/temp.zip');
    }

}