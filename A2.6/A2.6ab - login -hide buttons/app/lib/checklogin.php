<?php
// true als er een ingelogde account in de sessie staat.
function isLoggedIn(): bool
{
    return !empty($_SESSION['account']);
}
