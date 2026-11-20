
    <h1>A2.1a — PHP basics</h1>
    <p>Bekijk de PHP-code in je editor en het resultaat via localhost. Verander de waarden en ververs de pagina.</p>

    <h2>1. Tekst tonen met echo</h2>
    <?php
    // echo stuurt tekst naar de browser. Sluit een instructie af met ;
    echo "Welkom bij Software Developer!<br>";
    echo "Met echo kun je ook HTML tonen, zoals deze regeleinde-tag: &lt;br&gt;.<br>";
    ?>

    <h2>2. Variabelen en strings</h2>
    <?php
    // Een variabele begint met $. Een string is tekst tussen aanhalingstekens.
    $name = "Sam";
    $studyProgram = 'Software Developer';
    $age = 17;

    // Met een punt plak je strings en variabelen aan elkaar.
    echo "Ik ben " . $name . " en ik volg " . $studyProgram . ".<br>";
    echo "Ik ben " . $age . " jaar.<br>";

    // Dubbele aanhalingstekens vullen variabelen in; enkele doen dat niet.
    echo "Hallo $name!<br>";
    echo 'Hallo $name!<br>';
    ?>

    <h2>3. Rekenen met variabelen</h2>
    <?php
    $notebookCount = 4;
    $pricePerNotebook = 2.50; // Gebruik een punt voor een decimaal getal.
    $budget = 15;
    $total = $notebookCount * $pricePerNotebook;
    $remainingBudget = $budget - $total;

    echo "Kosten: " . $notebookCount . " × € " . $pricePerNotebook . " = € " . $total . "<br>";
    echo "Over van het budget: € " . $remainingBudget . "<br>";
    echo "Optellen (4 + 2): " . (4 + 2) . "<br>";
    echo "Aftrekken (4 - 2): " . (4 - 2) . "<br>";
    echo "Delen (10 / 2): " . (10 / 2) . "<br>";
    echo "Rest bij delen (10 % 3): " . (10 % 3) . "<br>";
    ?>

    <h2>4. Comments</h2>
    <p>Comments leggen code uit. PHP voert deze opmerkingen niet uit.</p>
    <?php
    // Dit is een comment van één regel.
    # Dit is ook een comment van één regel.
    /* Dit is een comment
       van meerdere regels. */
    echo "Deze echo wordt wel uitgevoerd.<br>";
    ?>

    <h2>5. Geldige en ongeldige variabelenamen</h2>
    <p>Begin na het dollarteken met een letter of underscore. Gebruik daarna letters, cijfers of underscores. Namen zijn hoofdlettergevoelig.</p>
    <?php
    $studentName = "Sam";
    $student2 = "Noor";
    $_class = "SD1A";
    echo $studentName . ", " . $student2 . ", " . $_class . "<br>";

    // Ongeldige voorbeelden staan in comments, zodat de demo blijft werken:
    // $2student = "Sam";       // Mag niet beginnen met een cijfer.
    // $student name = "Sam";   // Een spatie mag niet.
    // $student-name = "Sam";   // Een streepje is een minteken.
    // $student! = "Sam";       // Een uitroepteken mag niet.

    $name = "Sam";
    $Name = "Noor"; // Dit is een andere variabele dan $name.
    echo '$name: ' . $name . ' — $Name: ' . $Name;
    ?>
