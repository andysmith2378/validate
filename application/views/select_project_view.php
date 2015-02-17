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
        Please choose one of the following projects:
    </p>
    <form>
        <select>
            <?php foreach($projects as $project) { ?>
            <option value="<?php echo $project; ?>"><?php echo $project; ?></option>
            <?php } ?>
        </select>
    </form>

</body>