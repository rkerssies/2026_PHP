<h2>Eén record en aggregates</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</p>
<p>Donec ullamcorper nulla non metus auctor fringilla. Cras mattis consectetur purus sit amet fermentum. Maecenas faucibus mollis interdum. Vestibulum id ligula porta felis euismod semper. Nullam quis risus eget urna mollis ornare vel eu leo.</p>
<ul>
    <li><a href="index.php?controller=fruits_index">index.php?controller=fruits_index</a> toont alle rijen.</li>
    <li><a href="index.php?controller=fruits_index&orderby=name">index.php?controller=fruits_index&amp;orderby=name</a> sorteert op een toegestane kolom.</li>
    <li><a href="index.php?controller=fruits_show&id=1">index.php?controller=fruits_show&amp;id=1</a> haalt alleen het record met id 1 op.</li>
</ul>
<p><code>$_GET['id']</code> leest de id uit de URL. In <code>fruits_show.php</code> kun je die regel vervangen door <code>$id = $_POST['id'];</code>.</p>
<pre><code>SELECT * FROM fruits WHERE id = 1;</code></pre>

<h3>Database-relatie</h3>
<p>
    In <code>sql/schooldb_v3.sql</code> verwijst <code>users.favorite_fruit_id</code> naar <code>fruits.id</code>
    (foreign key <code>fk_users_favorite_fruit</code>). Dat is een one-to-many: één fruit, meerdere users.
</p>
<p>
    In <code>fruits_show.php</code> wordt die relatie gebruikt: bij een fruit-<code>id</code> komen de users
    met dat favoriete fruit (en het aantal) tevoorschijn onder <strong>Favoriet bij</strong>.
</p>
<pre><code>SELECT name, email FROM users WHERE favorite_fruit_id = 1 ORDER BY name ASC;
SELECT COUNT(*) AS favorite_total FROM users WHERE favorite_fruit_id = 1;</code></pre>
