<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Select a project to validate</title>
</head>
<body>

    <p>Please choose one of the following projects:</p>
    <form action="/validator/validate" method="post">
        <select name="project">
            <?php foreach($projects as $project) { ?>
            <option value="<?php echo $project; ?>"><?php echo $project; ?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Validate">
    </form>

</body>