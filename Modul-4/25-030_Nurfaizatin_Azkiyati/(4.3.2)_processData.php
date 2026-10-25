<?php
$errors = array();

if (isset($_POST['surname'])) {
    require '(4.3.2)_validate.inc';

    validateName($errors, $_POST, 'surname');

    if ($errors) {
        echo '<h1>Invalid, correct the following errors:</h1>';

        foreach ($errors as $field => $error) {
            echo "$field $error<br>";
        }
        
        include '(4.3.2)form.inc';
    } else {
        echo 'Form submitted successfully with no errors';
    }
} else {
    include 'form.inc';
}
?>
