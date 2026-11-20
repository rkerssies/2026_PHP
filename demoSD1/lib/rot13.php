<?php


    function doe_rot13()
    {
        // return true;
        // return false;
        // return "dfghjkl";
        // return [];
        // return ['sdfghjk'];



        $out = "vul teskt in...";        

        if(! empty($_POST))
        {
            $out = "ingvoerde tekst is: ". $_POST['textvalue'] . '<br>';
            $out .= 'versleuteld is: '.str_rot13($_POST['textvalue']);
        }
        return $out;
    }



?>