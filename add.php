<?php
    if(isset($_POST['btn-save'])){
        $text = $_POST['phrase_01'] . " " . $_POST['phrase_02'] . "\n"; 
        echo $text; 
        file_put_contents("file.txt", $text, FILE_APPEND);

    }

?>

<html>
    <body>
        <h1>I say YES to</h1>
        <form method="post">
            <select name="phrase_01">
                <option value="nette">nette</option>
                <option value="bescheiden">bescheiden</option>
                <option value="umwerfend">umwerfend</option>
            </select>   
            <select name="phrase_02">
                <option value="tofuburger">tofuburger</option>
                <option value="dracula">dracula</option>
                <option value="vorlesung">vorlesung</option>
                <option value="alien">alien</option>
            </select>   

            <input type="submit" name="btn-save">

        </form>

    </body>

</html>