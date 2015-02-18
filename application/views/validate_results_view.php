<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Validated</title>
</head>
<body>

<?php if(empty($errors)) { ?>
    <p>No errors found</p>
<?php } else { ?>
    <ul>
        <? foreach($errors as $errorMessage) { ?>
            <ul><?php echo $errorMessage ?></ul>
        <?php } ?>
    </ul>
<?php } ?>

</body>