<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to CodeIgniter</title>
</head>
<body>

<p>
    <?php
        $formatted = str_replace("\n", "<br/>", var_export($excel, true));
        echo $formatted;
    ?>
</p>

</body>