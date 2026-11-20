
    <h1>A2.1b — If en loops</h1>
    <p>Verander de postcode, leeftijd of boolean en ververs de pagina. De postcodes zijn fictieve lesvoorbeelden.</p>

    <h2>1. if-else: een postcode vergelijken</h2>
    <?php
    $postalCode = "8000AA"; // Tekst: een postcode bevat ook letters.
    echo "De postcode in de variabele is: " . $postalCode . "<br>";

    // = kent een waarde toe. === vergelijkt de waarde én het type.
    if ($postalCode == "8000AA") 
    {
        echo "Je postcode is gelijk aan 8000AA.<br>";
    } 
    else 
    {
        echo "Je hebt een andere postcode.<br>";
    }
    ?>

    <h2>2. if-elseif-else: meerdere mogelijkheden</h2>
    <?php
    // PHP schrijft dit als elseif. Alleen de eerste passende tak wordt uitgevoerd.
    if ($postalCode == "8000AA") 
    {
        echo "Je hoort bij postcode van de schoollocatie.<br>";
    } 
    elseif ($postalCode <= "8000AA") 
    {
        echo "gebieden in: Noord-Holland, Flevoland, Zuid-Holland, Utrecht, Gelderland, Zeeland, Noord-Brabant, Limburg, Overijssel en Drenthe.<br>";
    } 
    elseif ($postalCode >= "8000AA") 
    {
        echo "gebieden in: Friesland, Drenthe en Groningen.<br>";
    } 
    else 
    {
        echo "Vraag bij de balie welke schoollocatie bij je postcode hoort.<br>";
    }
    ?>

    <h2>3. &amp;&amp; betekent EN</h2>
    <p>Beide voorwaarden moeten waar zijn.</p>
    <?php
    $age = 17;
    
    if ($postalCode >= "8000" && $postalCode <= "8040" && $age >= 18) 
        {
        echo "Je mag deelnemen aan de verkiezingen in Zwolle.<br>";
    } 
    else 
    {
        echo "Je voldoet niet aan de voorwaarden voor de verkiezingen.<br>";
    }
    ?>

    <h2>4. || betekent OF</h2>
    <p>Minstens één voorwaarde moet waar zijn. Beide mogen ook waar zijn.</p>
    <?php
    if ($postalCode == "8000AA" || $postalCode == "8000AB") {
        echo "Je kunt je boeken ophalen bij dezelfde schoolbalie.<br>";
    } 
    else 
    {
        echo "Je haalt je boeken op bij een andere balie.<br>";
    }
    ?>

    <h2>5. for-loop: teller en rekensom</h2>
    <p>Een for-loop bevat een startwaarde, een voorwaarde en een verandering. Hier tonen we de tafel van 7.</p>
    <?php
    $multiplicationTable = 7;
    // Start op 1; herhaal zolang de teller <= 10 is; verhoog steeds met 1.
    for ($counter = 1; $counter <= 10; $counter++) 
    {
        $result = $counter * $multiplicationTable;
        echo "Teller " . $counter . ": " . $counter . " × " . $multiplicationTable . " = " . $result . "<br>";
    }
    ?>