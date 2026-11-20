<?php

function triggerController()
{

    if(empty($_GET['controller']))
    {   // als de url leeg is
         $controller = 'home';
    }
    else 
    {  // als er een menu-item is geklikt
       $controller = $_GET['controller'];
    }
   
    
    $path = 'app/controllers/' . $controller . '.php';
  
    if ( ! is_file($path))      // !  betekend NOT
    {
        http_response_code(404);
        echo   '<h2>404 — Pagina niet gevonden</h2>';
        exit;
    }
    include $path;

    return true;
}  