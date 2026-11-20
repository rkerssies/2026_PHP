<?php
// Geeft het fruitplaatje terug, of een placeholder als het bestand ontbreekt.
function fruitImage(string $href, string $name): string
{
    $relativeImage = str_replace('\\', '/', $href);
    $imageFile = basePath('public/' . ltrim($relativeImage, '/'));
    $imagesDir = realpath(basePath('public/images'));
    $realImage = realpath($imageFile);
    $imageExists = $imagesDir !== false
        && $realImage !== false
        && is_file($realImage)
        && str_starts_with($realImage, $imagesDir . DIRECTORY_SEPARATOR);

    if ($imageExists) {
        return '<img class="fruit-photo" src="' . escape($href) . '" alt="' . escape($name) . '" width="64" height="64">';
    }

    return '<span class="fruit-placeholder"><img src="images/logo/logo.png" alt="" width="40" height="40"><span>Geen afbeelding</span></span>';
}
