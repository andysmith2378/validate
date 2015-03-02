<?php
    $projectsDir = __DIR__ . "/upload/";

    $projectsList = array();

    if ($projectsDirHandle = opendir($projectsDir)) {
        while (false !== ($entry = readdir($projectsDirHandle))) {
            if ($entry != "." && $entry != "..") {
                $projectsList[] = $entry;
            }
        }
        closedir($projectsDirHandle);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Select a project to validate</title>
</head>
<body>

<p>Please choose one of the following projects:</p>
<form action="results.php" method="post">
    <select name="project">
        <?php foreach($projectsList as $project) { ?>
            <option value="<?php echo $project; ?>"><?php echo $project; ?></option>
        <?php } ?>
    </select>
    <input type="submit" value="Validate">
</form>

</body>
</html>