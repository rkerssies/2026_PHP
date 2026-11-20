<?php
// Bij het eerste bezoek is $_POST['versturen'] niet aanwezig.
// isset is true als de key bestaat en de waarde niet null is.
// Een lege string bestaat wél: isset ziet die dus als aanwezig.
if (!isset($_POST['versturen'])) {
    $issetMessage = 'Je hebt het formulier nog niet verstuurd.';
} else {
    $issetMessage = 'Het formulier is verstuurd.';
}

$resultMessage = '';

// Verwerk pas gegevens nadat op de submitknop is geklikt.
if (isset($_POST['versturen'])) {
    // empty is ook true bij een ontbrekende key en geeft dan geen waarschuwing.
    // Let op: empty ziet onder andere ook de string "0" als leeg.
    if (empty($_POST['voornaam']) || empty($_POST['klas']) || empty($_POST['opleiding'])) {
        $resultMessage = 'Vul je voornaam, klas en opleiding in.';
    } else {
        // Text-inputs komen normaal als strings binnen.
        // htmlspecialchars toont ingevoerde HTML als tekst in de browser.
        $firstName = htmlspecialchars($_POST['voornaam'], ENT_QUOTES, 'UTF-8');
        $class = htmlspecialchars($_POST['klas'], ENT_QUOTES, 'UTF-8');
        $studyProgram = htmlspecialchars($_POST['opleiding'], ENT_QUOTES, 'UTF-8');

        $resultMessage = 'Hallo ' . $firstName . ', je zit in klas ' . $class . ' en volgt ' . $studyProgram . '.';
    }
}
?>
    <h1>A2.1d — Forms en POST</h1>
    <p>Vul het formulier in en klik op Versturen. Probeer ook een veld leeg te laten.</p>

    <h2>1. Het formulier</h2>
    <p>action="" verstuurt naar dezelfde pagina. method="post" verstuurt de gegevens in de request-body. Het name-attribuut wordt de key in $_POST.</p>

    <!-- id verbindt een input met zijn label; name bepaalt de key voor PHP. -->
    <!-- Geen required: zo kun je hieronder zelf empty() demonstreren. -->
    <form action="" method="post">
        <p>
            <label for="voornaam">Voornaam:</label>
            <input type="text" id="voornaam" name="voornaam">
        </p>
        <p>
            <label for="klas">Klas:</label>
            <input type="text" id="klas" name="klas">
        </p>
        <p>
            <label for="opleiding">Opleiding:</label>
            <input type="text" id="opleiding" name="opleiding">
        </p>
        <button type="submit" name="versturen" value="1">Versturen</button>
    </form>

    <h2>2. isset: is er een waarde verstuurd?</h2>
    <p><?= $issetMessage ?></p>

    <h2>3. empty: zijn de velden ingevuld?</h2>
    <p><?= $resultMessage ?></p>
