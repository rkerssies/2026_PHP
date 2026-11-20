<?php
// Alle PHP-logica staat vóór de doctype. Variabelenamen zijn in het Engels.
// In de HTML renderen we alleen binnen het article-element, met korte echo-tags.
$studentName = '';
$postcode = '';
$quantityText = '1';
$message = 'Vul het formulier in om een fruitbox voor de klas te bestellen.';
$resultHtml = '';

// Een numeriek geïndexeerde array: de keys zijn 0, 1 en 2.
$subjects = ['PHP', 'HTML', 'CSS'];
$subjectHtml = '';
for ($index = 0; $index < count($subjects); $index++) {
    $subjectHtml .= '<li>Key ' . $index . ': ' . $subjects[$index] . '</li>';
}

// Een associatieve array met benoemde keys.
$school = ['name' => 'Demo College', 'class' => 'SD1A', 'location' => 'Zwolle'];
$schoolHtml = '';
foreach ($school as $key => $value) {
    $schoolHtml .= '<li>' . $key . ': ' . $value . '</li>';
}

// Een multidimensionale array: iedere fruitsoort heeft eigen gegevens.
$fruits = [
    'apple' => ['name' => 'Appel', 'color' => 'Rood', 'price' => 0.60],
    'banana' => ['name' => 'Banaan', 'color' => 'Geel', 'price' => 0.70],
    'pear' => ['name' => 'Peer', 'color' => 'Groen', 'price' => 0.80]
];
$fruitRowsHtml = '';
$boxPrice = 0;
foreach ($fruits as $fruitKey => $fruit) {
    $boxPrice += $fruit['price'];
    $fruitRowsHtml .= '<tr><th>' . $fruitKey . '</th>';
    foreach ($fruit as $key => $value) {
        if ($key === 'price') {
            $value = '€ ' . number_format($value, 2, ',', '.');
        }
        $fruitRowsHtml .= '<td>' . $value . '</td>';
    }
    $fruitRowsHtml .= '</tr>';
}
$boxPriceText = number_format($boxPrice, 2, ',', '.');

// isset controleert of de submitknop is verstuurd.
if (isset($_POST['submit'])) {
    // Alleen tekst verwerken. Ontbrekende velden krijgen een lege string.
    $studentName = isset($_POST['studentName']) && is_string($_POST['studentName']) ? trim($_POST['studentName']) : '';
    $postcode = isset($_POST['postcode']) && is_string($_POST['postcode']) ? strtoupper(str_replace(' ', '', trim($_POST['postcode']))) : '';
    $quantityText = isset($_POST['quantity']) && is_string($_POST['quantity']) ? trim($_POST['quantity']) : '';

    // || betekent OF. empty is ook true bij de string "0".
    if (empty($studentName) || empty($postcode) || empty($quantityText)) {
        $message = 'Vul alle velden in. Het aantal moet minstens 1 zijn.';
    } elseif (!ctype_digit($quantityText) || (int) $quantityText < 1 || (int) $quantityText > 10) {
        $message = 'Vul een heel aantal van 1 tot en met 10 in.';
    } else {
        $quantity = (int) $quantityText;
        $total = $quantity * $boxPrice;

        // if-elseif-else: alleen de eerste passende tak wordt uitgevoerd.
        // De postcodes zijn fictieve lesvoorbeelden.
        if ($postcode === '8000AA') {
            $location = 'Locatie A';
        } elseif ($postcode === '8000AB') {
            $location = 'Locatie B';
        } else {
            $location = 'De centrale schoolbalie';
        }

        // && betekent EN: beide voorwaarden moeten waar zijn.
        if ($postcode === '8000AA' && $quantity >= 3) {
            $discount = 1.00;
        } else {
            $discount = 0;
        }
        $amountDue = $total - $discount;

        // || betekent OF: minimaal één voorwaarde moet waar zijn.
        if ($postcode === '8000AA' || $postcode === '8000AB') {
            $pickupMessage = 'Je haalt de boxen op bij de balie van je eigen locatie.';
        } else {
            $pickupMessage = 'Je haalt de boxen op bij de centrale schoolbalie.';
        }

        $message = 'Je demo-bestelling is berekend.';
        $safeName = htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8');
        $resultHtml = '<h3>Bestelling voor ' . $safeName . '</h3>';
        $resultHtml .= '<p>' . $quantity . ' box(en) × € ' . $boxPriceText . ' = € ' . number_format($total, 2, ',', '.') . '</p>';
        $resultHtml .= '<p>Korting: € ' . number_format($discount, 2, ',', '.') . ' — Te betalen: <strong>€ ' . number_format($amountDue, 2, ',', '.') . '</strong></p>';
        $resultHtml .= '<p>' . $location . '. ' . $pickupMessage . '</p><ul>';

        // for-loop: toon de teller en een rekensom per box.
        for ($counter = 1; $counter <= $quantity; $counter++) {
            $subtotal = $counter * $boxPrice;
            $resultHtml .= '<li>Teller ' . $counter . ': ' . $counter . ' × € ' . $boxPriceText . ' = € ' . number_format($subtotal, 2, ',', '.') . ' vóór korting</li>';
        }
        $resultHtml .= '</ul>';
    }
}

// Invoer eerst escapen: ingevoerde HTML blijft tekst, ook in value-attributen.
$studentNameHtml = htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8');
$postcodeHtml = htmlspecialchars($postcode, ENT_QUOTES, 'UTF-8');
$quantityHtml = htmlspecialchars($quantityText, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A2.3ab — Fruitbox voor de klas</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header><h1>A2.3ab — Fruitbox voor de klas</h1></header>
    <main>
        <article>
            <nav aria-label="Demopagina’s">
                <a href="A2.2a-combi-basics.php">Combi basics</a>
                <a href="A2.2b-url-pages.php?controller=home">URL-pagina’s</a>
                <a href="A2.2cd-databases.php">Databases</a>
            </nav>
            <p>Een combinatie van variabelen, strings, rekenen, if, loops, arrays en POST. Iedere box bevat één appel, één banaan en één peer.</p>
            <section>
                <h2>1. Gegevens uit arrays</h2>
                <div class="columns">
                    <section><h3>Vakken: numerieke keys + for</h3><ul><?= $subjectHtml ?></ul></section>
                    <section><h3>School: associatieve keys + foreach</h3><ul><?= $schoolHtml ?></ul></section>
                </div>
                <h3>Fruit: multidimensionale array + twee foreach-loops</h3>
                <table>
                    <thead><tr><th>Key</th><th>Naam</th><th>Kleur</th><th>Prijs</th></tr></thead>
                    <tbody><?= $fruitRowsHtml ?></tbody>
                </table>
                <p>Prijs per fruitbox: <strong>€ <?= $boxPriceText ?></strong></p>
            </section>
            <section>
                <h2>2. Formulier met POST</h2>
                <p>action="" stuurt naar dit bestand. name wordt de key in $_POST. isset en empty controleren de invoer.</p>
                <form action="" method="post">
                    <label for="studentName">Voornaam</label>
                    <input type="text" id="studentName" name="studentName" value="<?= $studentNameHtml ?>">
                    <label for="postcode">Postcode, bijvoorbeeld 8000AA</label>
                    <input type="text" id="postcode" name="postcode" value="<?= $postcodeHtml ?>">
                    <label for="quantity">Aantal fruitboxen (1–10)</label>
                    <input type="text" id="quantity" name="quantity" value="<?= $quantityHtml ?>">
                    <button type="submit" name="submit" value="1">Bereken bestelling</button>
                </form>
            </section>
            <section>
                <h2>3. Uitkomst: if + rekenen + for-loop</h2>
                <p class="message"><?= $message ?></p>
                <?= $resultHtml ?>
                <p>Demo: probeer Sam, 8000AA en 3 boxen. De uitkomst is € 6,30 min € 1,00 korting = € 5,30. Probeer daarna 8000AB of een leeg veld.</p>
            </section>
        </article>
    </main>
    <footer><p>Schooldemo — verander de code in je editor en ververs via localhost.</p></footer>
</body>
</html>
