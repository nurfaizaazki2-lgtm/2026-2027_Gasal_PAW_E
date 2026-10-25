<?php
require '(4.3.1)_validate.inc';

$errors = array();

validateName($errors, $_POST, 'surname');

if ($errors)
{
    echo 'Errors:<br/>';

    foreach ($errors as $field => $error)
        echo "$field $error<br/>";
}
else
    echo 'Data OK!';
?>
