
<?php
    print_r($_POST);
    $out = 'vul form in';
    if(!empty($_POST['getal']))
    {
        $out = '<Table>';
        for($i = 1 ; $i <=10 ; $i++)
        {
            $out .= '<tr> <td>3 </td><td> '.$i .' = ' . ($_POST['getal'] * 3).'</td></tr>';
        }
        $out .= '</table>';
    }

?>

<html>
<body>
    <form method="post" action="index.php">
        <label>getal</label>
        <input name="getal">
        <br>
        <button>knop</button>
    </form>
    <?php echo $out; ?>
</body>
</html>