<?php


    function som($x, $pow, $afr = 2)
    {
        if($x < 10) 
        {
            $x = 7;
        }
        $getal = round(pow($x, $pow), $afr);

        return $getal;
    }
