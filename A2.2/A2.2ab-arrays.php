
    <h1>A2.1c — Arrays en meer</h1>
    <p>Een array bewaart meerdere waarden. Iedere waarde heeft een key (sleutel).</p>

    <h2>1. Een array maken met []</h2>
    <?php
    // Bij deze array maakt PHP automatisch numerieke keys: 0, 1 en 2.
    $subjects = ["PHP", "HTML", "JavaScript"];
    ?>

    <h2>2. De inhoud bekijken met print_r</h2>
    <p>print_r toont keys en waarden. De HTML-tag pre houdt de inspringing en regeleinden zichtbaar.</p>
    <?php
    echo "<pre>";
    print_r($subjects);
    echo "</pre>";
    ?>

    <h2>3. Enkele waarden tonen met hun key</h2>
    <?php
    // De eerste waarde staat bij key 0, niet bij key 1.
    echo "Eerste vak (key 0): " . $subjects[0] . "<br>";
    echo "Derde vak (key 2): " . $subjects[2] . "<br>";
    ?>

    <h2>4. Een for-loop door een numeriek geïndexeerde array</h2>
    <?php
    // count telt het aantal waarden. Deze array heeft opeenvolgende keys vanaf 0.
    // De laatste key is count($subjects) - 1. Daarom gebruiken we < en niet <=.
    for ($i = 0; $i < count($subjects); $i++) {
        echo "Key " . $i . ": " . $subjects[$i] . "<br>";
    }
    ?>

    <h2>5. Associatieve array: benoemde keys</h2>
    <?php
    // Met => koppel je een key aan een waarde.
    $student = [
        "naam" => "Sam",
        "klas" => "SD1A",
        "opleiding" => "Software Developer"
    ];

    echo "Naam via key 'naam': " . $student["naam"] . "<br>";
    echo "Klas via key 'klas': " . $student["klas"] . "<br>";

    // foreach geeft bij elke ronde de volgende key en waarde.
    foreach ($student as $key => $value) {
        echo $key . ": " . $value . "<br>";
    }
    ?>

    <h2>6. Multidimensionale associatieve array</h2>
    <p>De buitenste array heeft studentnummers als keys. Iedere student bevat weer een associatieve array.</p>
    <?php
    $students = [
        "s001" => ["naam" => "Sam", "klas" => "SD1A", "woonplaats" => "Zwolle"],
        "s002" => ["naam" => "Noor", "klas" => "SD1B", "woonplaats" => "Kampen"],
        "s003" => ["naam" => "Milan", "klas" => "SD1A", "woonplaats" => "Meppel"]
    ];

    echo "Eén waarde direct opvragen: " . $students["s002"]["naam"] . "<br>";

    // De buitenste foreach kiest een student.
    foreach ($students as $studentNumber => $data) {
        echo "<h3>Student " . $studentNumber . "</h3>";

        // De binnenste foreach loopt door de gegevens van die student.
        foreach ($data as $key => $value) {
            echo $key . ": " . $value . "<br>";
        }
    }
    ?>
