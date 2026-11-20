<?php
// Deze les voert geen databasequeries uit; leerlingen gebruiken phpMyAdmin.
$databaseName = 'school_db';
$practiceDatabaseName = $databaseName . '_practice';
$tableName = 'fruits';
$importPath = 'sql/school_db.sql';
$importSql = is_readable(__DIR__ . '/' . $importPath) ? file_get_contents(__DIR__ . '/' . $importPath) : false;
$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$columns = [
    ['id', 'INT UNSIGNED', 'Geheel getal zonder negatieve waarden. PRIMARY, A_I aan, NULL uit.'],
    ['name', 'VARCHAR(100)', 'Korte tekst, maximaal 100 tekens. NULL uit.'],
    ['color', 'VARCHAR(80)', 'Kleur als korte tekst. NULL uit.'],
    ['sweetness', 'TINYINT UNSIGNED', 'Klein geheel getal (0–255); in deze les gebruiken we 1–10. NULL uit.'],
    ['season', 'VARCHAR(40)', 'Seizoen als korte tekst. NULL uit.'],
    ['price', 'DECIMAL(6,2)', 'Exact kommagetal: 6 cijfers totaal, waarvan 2 achter de komma. NULL aan, standaard NULL.'],
    ['description', 'TEXT', 'Langere vrije tekst. NULL aan.'],
    ['is_available', 'BOOLEAN', 'Waar/onwaar: 1 of 0. MySQL/MariaDB slaat dit op als TINYINT(1). NULL uit, standaard 1.'],
    ['harvested_on', 'DATE', 'Datum in YYYY-MM-DD. NULL aan, standaard NULL.'],
    ['created_at', 'DATETIME', 'Datum en tijd. NULL uit, standaard CURRENT_TIMESTAMP.']
];
$examples = [
    ['Alles bekijken', 'SELECT * FROM fruits;', 'Na import zijn er 50 records; phpMyAdmin kan die over meerdere pagina’s verdelen.'],
    ['Kolommen kiezen', 'SELECT name, color FROM fruits;', 'Je ziet alleen naam en kleur.'],
    ['Filteren', 'SELECT name, sweetness FROM fruits WHERE sweetness >= 8;', 'Elke getoonde rij heeft een zoetheid van minstens 8.'],
    ['Sorteren en beperken', 'SELECT * FROM fruits ORDER BY name ASC LIMIT 10;', 'Je ziet de eerste 10 namen op alfabet.'],
    ['Tellen', 'SELECT COUNT(*) AS total FROM fruits;', 'Direct na import: één resultaatrij met total = 50.'],
    ['Eén record zoeken', 'SELECT * FROM fruits WHERE id = 1;', 'In een verse import vind je de appel.'],
    ['Toevoegen', "INSERT INTO fruits (name, color, sweetness, season) VALUES ('Demo-appel', 'Rood', 7, 'Herfst');", 'Voer eenmaal uit: 51 records. id en created_at worden automatisch ingevuld.'],
    ['Eerst de selectie controleren', "SELECT * FROM fruits WHERE name = 'Demo-appel';", 'Controleer dat alleen jouw demorij wordt gevonden, vóór UPDATE of DELETE.'],
    ['Wijzigen', "UPDATE fruits SET sweetness = 8 WHERE name = 'Demo-appel';", 'Voer daarna de SELECT opnieuw uit: sweetness is nu 8.'],
    ['Verwijderen', "DELETE FROM fruits WHERE name = 'Demo-appel';", 'Voer SELECT en COUNT opnieuw uit: de demorij is weg en total is weer 50.']
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A2.2cd — Basics databases: lesplan phpMyAdmin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header><h1>A2.2cd — Basics databases</h1></header>
<main><article>
    <nav aria-label="Demopagina’s">
        <a href="A2.2a-combi-basics.php">Combi basics</a>
        <a href="A2.2b-url-pages.php?controller=home">URL-pagina’s</a>
        <a href="A2.2cd-databases.php">Databases</a>
    </nav>
    <p>Lesplan phpMyAdmin · circa 90 minuten · voordoen, samen oefenen en zelfstandig controleren.</p>
    <p>Na deze les kun je een database en tabel maken, SQL importeren, gegevens opvragen en wijzigen, een ERD bekijken en het verschil uitleggen tussen een leeg resultaat en een queryfout.</p>
    <p>phpMyAdmin is de beheerinterface. MySQL/MariaDB bewaart de gegevens. Een database bevat tabellen; een tabel bevat kolommen (velden) en rijen (records). Deze pagina geeft uitleg: voer de SQL zelf uit in phpMyAdmin.</p>

    <section>
        <h2>1. Starten en database aanmaken — 10 minuten</h2>
        <ol>
            <li>Start Apache en MySQL in XAMPP. Open <a href="http://localhost/phpmyadmin/">phpMyAdmin</a>.</li>
            <li>Kies <strong>Nieuw</strong> en maak <code><?= escape($practiceDatabaseName) ?></code> met collatie <code>utf8mb4_unicode_ci</code>. Die ondersteunt onder andere accenten en emoji.</li>
            <li>Selecteer de nieuwe database links. Controleer bovenaan steeds welke database actief is.</li>
        </ol>
        <p><strong>Controle:</strong> je database bestaat, maar bevat nog geen tabellen. De handmatige oefening gebruikt deze oefendatabase; de import gebruikt straks <code><?= escape($databaseName) ?></code>.</p>
    </section>

    <section>
        <h2>2. Tabel en kolommen aanmaken — 20 minuten</h2>
        <ol>
            <li>Maak in de oefendatabase de tabel <code><?= escape($tableName) ?></code> met <?= count($columns) ?> kolommen.</li>
            <li>Neem onderstaande namen en instellingen over. Vul bij VARCHAR de lengte apart in; bij DECIMAL vul je <code>6,2</code> in.</li>
            <li>Kies bij <code>id</code> index <strong>PRIMARY</strong> en vink <strong>A_I</strong> (AUTO_INCREMENT) aan. Gebruik InnoDB als opslagengine en sla de tabel op.</li>
            <li>Bekijk de tab <strong>Structuur</strong> en daarna <strong>Verkennen</strong>. De tabel bestaat, maar is nog leeg.</li>
        </ol>
        <table>
            <thead><tr><th>Engelse kolomnaam</th><th>Datatype</th><th>Betekenis en instelling</th></tr></thead>
            <tbody>
            <?php foreach ($columns as $column): ?>
                <tr><th><code><?= escape($column[0]) ?></code></th><td><?= escape($column[1]) ?></td><td><?= escape($column[2]) ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p>Een primaire sleutel identificeert één record uniek. AUTO_INCREMENT vult de id in bij INSERT: geef id zelf niet mee. Verwijderde nummers kunnen gaten achterlaten. Een id is dus geen telling van het aantal records.</p>
        <p><code>NULL</code> betekent onbekend of niet ingevuld; het is iets anders dan 0 of een lege tekst. Een standaardwaarde wordt gebruikt als je de kolom weglaat bij INSERT. De afspraak sweetness 1–10 wordt hier niet door TINYINT afgedwongen.</p>
    </section>

    <section>
        <h2>3. SQL importeren: 50 fruitsoorten — 10 minuten</h2>
        <ol>
            <li>Download <a href="<?= escape($importPath) ?>" download>school_db.sql</a>.</li>
            <li>Ga naar de startpagina van phpMyAdmin, kies <strong>Importeren</strong>, selecteer het bestand en kies formaat SQL. Klik op <strong>Starten/Uitvoeren</strong>.</li>
            <li>Het bestand maakt <code><?= escape($databaseName) ?></code>, selecteert die met USE, maakt tabel <code><?= escape($tableName) ?></code> en voegt 50 fruitsoorten toe.</li>
            <li>Selecteer die database en tabel. Bekijk <strong>Structuur</strong> en <strong>Verkennen</strong>. Voer in de SQL-tab <code>SELECT COUNT(*) AS total FROM fruits;</code> uit: verwacht 50.</li>
        </ol>
        <p>Gebruik een database waarin fruits nog niet bestaat. Dit bestand verwijdert geen bestaande tabellen en is bedoeld voor één import. Bestaat fruits al? Stop en controleer de inhoud; gebruik voor een nieuwe demo een andere databasenaam in zowel CREATE DATABASE als USE in een kopie van de import. Herhaald importeren kan fouten of dubbele gegevens opleveren.</p>
        <p>Mag je account geen database maken? Laat de docent een lege database klaarzetten, verwijder CREATE DATABASE en USE uit een kopie en importeer die kopie in de geselecteerde database.</p>
        <p>Kleuren, seizoenen en zoetheid zijn vereenvoudigde lesdata. price, description en harvested_on beginnen als NULL; is_available begint als 1 en created_at krijgt het importtijdstip.</p>
        <details><summary>Bekijk het volledige importbestand</summary>
            <?php if ($importSql !== false): ?>
                <pre><code><?= escape($importSql) ?></code></pre>
            <?php else: ?>
                <p>Het importbestand is niet beschikbaar. Vraag de docent om het SQL-bestand.</p>
            <?php endif; ?>
        </details>
    </section>

    <section>
        <h2>4. Queries uitvoeren in de SQL-tab — 20 minuten</h2>
        <p>Selecteer de geïmporteerde database en open <strong>SQL</strong>. Voer één voorbeeld per keer uit. Lees steeds de melding en vergelijk de uitkomst met de verwachting. Tekstwaarden staan tussen enkele aanhalingstekens; elke opdracht eindigt met een puntkomma.</p>
        <?php foreach ($examples as $index => $example): ?>
            <h3><?= $index + 1 ?>. <?= escape($example[0]) ?></h3>
            <pre><code><?= escape($example[1]) ?></code></pre>
            <p><?= escape($example[2]) ?></p>
        <?php endforeach; ?>
        <p>Gebruik bij UPDATE en DELETE altijd een bewuste WHERE-selectie: zonder WHERE geldt de opdracht voor alle rijen. Gebruik voor één specifiek record bij voorkeur de primaire sleutel. Nul gewijzigde rijen kan ook betekenen dat de waarde al gelijk was; controleer met SELECT.</p>
    </section>

    <section>
        <h2>5. ERD bekijken in Designer — 10 minuten</h2>
        <ol>
            <li>Selecteer de database, niet alleen de tabel. Open <strong>Designer/Ontwerper</strong>, eventueel onder <strong>Meer</strong>.</li>
            <li>Zoek het blok fruits en wijs de kolommen en de primaire sleutel id aan. Verplaats het blok om het diagram overzichtelijk te maken.</li>
            <li>Leg uit: een ERD (Entity Relationship Diagram) toont tabellen, sleutels en relaties. Met alleen fruits zie je één tabel zonder relatielijnen; dat is correct.</li>
        </ol>
        <p>Een relatie tussen tabellen gebruikt een foreign key die naar een sleutel van een andere tabel verwijst. Alleen dezelfde kolomnaam gebruiken maakt nog geen relatie. Als uitbreiding kun je later een tabel suppliers koppelen via supplier_id.</p>
        <p>Ontbreekt Designer of kun je de indeling niet bewaren? Controleer eerst het databasemenu en Meer. Laat de docent daarna de phpMyAdmin-configuratieopslag controleren. Gebruik ondertussen Structuur om kolommen en sleutels te bekijken. Zie de <a href="https://docs.phpmyadmin.net/en/latest/relations.html">phpMyAdmin-uitleg over relaties en Designer</a>.</p>
    </section>

    <section>
        <h2>6. Geen records of een foute query? — 15 minuten</h2>
        <h3>Een leeg resultaat is geen SQL-fout</h3>
        <pre><code>SELECT * FROM fruits WHERE sweetness &gt; 10;
SELECT COUNT(*) AS total FROM fruits;
SELECT * FROM fruits LIMIT 5;</code></pre>
        <p>Voer deze opdrachten afzonderlijk uit. De eerste levert met onze lesdata nul rijen op, maar is geldig. Geeft COUNT 50 en de laatste query wel rijen? Dan sluit je filter alles uit. Geeft COUNT 0? Dan is de tabel leeg. COUNT geeft zelf nog steeds één resultaatrij met de waarde 0.</p>
        <p>Controleer de geselecteerde database en tabel. Is de tabel echt leeg, controleer dan de importmelding en of de INSERT is uitgevoerd. Importeer niet blind opnieuw. Wil je ontbrekende waarden zoeken, gebruik dan <code>WHERE price IS NULL</code>, niet <code>price = NULL</code>.</p>
        <h3>Een fout herkennen en stap voor stap herstellen</h3>
        <ol>
            <li>Lees de volledige foutmelding, foutcode en het stukje SQL bij de fout. Bewaar de query die je uitvoerde.</li>
            <li>Controleer je actieve database met <code>SELECT DATABASE();</code>, tabellen met <code>SHOW TABLES;</code> en kolommen met <code>DESCRIBE fruits;</code>.</li>
            <li>Begin met <code>SELECT * FROM fruits LIMIT 5;</code>. Voeg daarna kolommen, WHERE en sortering één voor één toe. Test na iedere wijziging.</li>
            <li>Vergelijk de uitkomst met wat je verwacht: juiste kolommen, juiste rijen en juist aantal. Een query zonder foutmelding kan inhoudelijk nog fout zijn.</li>
        </ol>
        <table>
            <thead><tr><th>Melding</th><th>Wat controleer je?</th></tr></thead>
            <tbody>
                <tr><td>1064 — SQL syntax</td><td>Controleer spelling van SELECT/FROM, komma’s, aanhalingstekens en haakjes rond het gemelde fragment.</td></tr>
                <tr><td>1046 — No database selected</td><td>Selecteer de bedoelde database links en probeer opnieuw.</td></tr>
                <tr><td>1146 — Table does not exist</td><td>Controleer database, tabelnaam fruits en of CREATE TABLE gelukt is.</td></tr>
                <tr><td>1054 — Unknown column</td><td>Vergelijk de kolomnaam met Structuur: name is Engels, naam bestaat hier niet.</td></tr>
                <tr><td>1050 — Table already exists</td><td>De tabel bestaat al. Controleer of de import eerder is uitgevoerd; kies zo nodig een nieuwe oefendatabase.</td></tr>
                <tr><td>1062 — Duplicate entry</td><td>Een unieke sleutel bestaat al. Laat id weg bij INSERT en laat AUTO_INCREMENT het nummer bepalen.</td></tr>
                <tr><td>Toegang geweigerd</td><td>Controleer account en rechten met de docent; dit los je niet op door SELECT anders te spellen.</td></tr>
            </tbody>
        </table>
        <p><strong>Fout oefenen:</strong> voer <code>SELECT naam FROM fruits;</code> uit. Verklaar de fout en herstel de query naar <code>SELECT name FROM fruits;</code>. Vergelijk dit met het geldige, lege resultaat bij sweetness &gt; 10.</p>
    </section>

    <section>
        <h2>Afsluitende opdracht — 5 minuten</h2>
        <p>Toon naam en kleur van fruit met sweetness minstens 8, alfabetisch op naam. Laat het aantal records zien, wijs de primaire sleutel aan in Designer en leg aan je buur uit waarom nul gevonden records iets anders is dan een SQL-fout. Eindig met 50 records en zonder Demo-appel.</p>
    </section>
</article></main>
<footer><p>A2.2cd — phpMyAdmin gebruiken, structuur begrijpen en queryresultaten controleren.</p></footer>
</body>
</html>
