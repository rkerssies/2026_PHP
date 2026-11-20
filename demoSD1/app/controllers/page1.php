<?php
    print_r($_POST);

    $som = 'hallo';

    if( ! empty($_POST['number1']) && ! empty($_POST['number2']))
    {
        $som = $_POST['number1'] + $_POST['number2'];
    }
  
?>


<h1>Page 1</h1>

<form method="post" action="index.php?controller=page1">
    <input name="number1">
    <input name="number2">
    <input type="submit">
</form>

<?php echo $som; ?>