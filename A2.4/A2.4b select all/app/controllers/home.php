<h2>Fruit ophalen met SELECT</h2>
<ol>
    <li>Start Apache en MySQL in XAMPP.</li>
    <li>Importeer <a href="sql/school_db.sql" download>school_db.sql</a> via phpMyAdmin. Dit bevat de tabel fruits met 50 fruitsoorten. Bestaat die tabel al met lesdata? Dan hoef je niet opnieuw te importeren.</li>
    <li>Controleer de verbinding in <code>app/config/database.php</code>.</li>
    <li>Open <a href="index.php?controller=fruits_index">index.php?controller=fruits_index</a>.</li>
</ol>
<p>De URL-querystring wordt gelezen via <code>$_GET['controller']</code>. De router laadt <code>app/controllers/fruits_index.php</code>. Deze controller maakt de mysqli-verbinding, voert de SELECT-query uit en zet alle kolomwaarden in een tabel.</p>
<pre><code>SELECT * FROM fruits ORDER BY id ASC;</code></pre>
<p><code>*</code> betekent alle kolommen. Zonder WHERE krijg je alle records. <code>fetch_assoc()</code> leest steeds één record als array met de kolomnamen als keys.</p>
