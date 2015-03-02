<?php

    $project = $_POST['project'];

    $errors = doValidation($project);

    function doValidation($project = "test") {
        require_once 'third_party/PHPExcel/PHPExcel.php';

        $errors = array();

        $projectPath = __DIR__ . "/upload/" . $project . "/";

        // Ensure the project path exists
        if(!file_exists($projectPath)) {
            $errors[] = "Invalid project path given";
            return $errors;
        }

        $excelFile = "";

        if ($projectDirHandle = opendir($projectPath)) {
            while (false !== ($entry = readdir($projectDirHandle))) {
                if (pathinfo($entry, PATHINFO_EXTENSION) == "xlsx") {
                    $excelFile = $entry;
                }
            }
            closedir($projectDirHandle);
        }

        if(empty($excelFile)) {
            $errors[] = "No Media Grid file found";
            return $errors;
        }

        $objPHPExcel = PHPExcel_IOFactory::load($projectPath . $excelFile);

        // TODO Check if we can safely assume only one worksheet per file
        // Iterate through all the sheets in the excel workbook and store as an array
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $arrayData[$worksheet->getTitle()] = $worksheet->toArray();
        }

        $mediaGrid = array();
        $resCodeLabel = "";
        $resCategoryLabel = "";
        $resTitleLabel = "";

        // Grab all the data we need for validation from the media grid spreadsheet
        foreach ($arrayData['CLA Media Grid'] as $row_number => $entry) {
            if($row_number == 0) {
                $resCodeLabel = $entry[0];
                $resCategoryLabel = $entry[4];
                $resTitleLabel = $entry[6];
            }
            else {
                // Don't include rows that have resource category 'CH: Chapter pdfs'
                if($entry[4] != "CH: Chapter pdfs") {
                    $mediaGrid[] = array(
                        $resCodeLabel => trim($entry[0]),
                        $resCategoryLabel => $entry[4],
                        $resTitleLabel => $entry[6]
                    );
                }
            }
        }

        $contentFiles = array();

        // Scan the 'Final Content' folder
        if ($projectsDirHandle = opendir($projectPath . "Final Content/")) {
            while (false !== ($entry = readdir($projectsDirHandle))) {
                if ($entry != "." && $entry != ".." && $entry != "Chapter PDFs") {
                    $contentFiles[] = array(
                        "filename" => $entry,
                        "listedInMediaGrid" => FALSE
                    );
                }
            }
            closedir($projectsDirHandle);
        }
        else {
            $errors[] = "Could not open Final Content folder";
            return $errors;
        }

        // Loop through every entry in the Media Grid, and verify that the Resource Code references a file that exists
        // in the Final Content directory
        for ($i = 0; $i < count($mediaGrid); $i++) {

            $fileInMediaGrid = $mediaGrid[$i][$resCodeLabel];
            $fileFound = FALSE;

            for ($j = 0; $j < count($contentFiles); $j++) {
                $contentFileName = pathinfo($contentFiles[$j]['filename'], PATHINFO_FILENAME);
                if ($fileInMediaGrid == $contentFileName) {
                    $contentFiles[$j]['listedInMediaGrid'] = TRUE;
                    $fileFound = TRUE;
                }
            }

            if (!$fileFound) {
                $errors[] = "Could not find a file matching resource code " . $fileInMediaGrid . " (row " . ($i + 2) . ")";
            }
        }

        // Loop through all the files in the Final Content list and check that have now all been marked
        // as listed in the Media Grid
        for($i = 0; $i < count($contentFiles) ; $i++) {
            if(!$contentFiles[$i]['listedInMediaGrid']) {
                $errors[] = "File " . $contentFiles[$i]['filename'] . " exists in Final Content folder but is not listed in the Media Grid";
            }
        }

        // Loop through Media Grid and check Resource categories are all valid
        for($i = 0; $i < count($mediaGrid) ; $i++) {
            if(!in_array($mediaGrid[$i][$resCategoryLabel], $this->allowedResourceCategories)) {
                $errors[] = "Row ". $i . ": Resource category " . $mediaGrid[$i][$resCategoryLabel] . " not allowed";
            }
        }

        return $errors;

    }

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
        <?php foreach($errors as $errorMessage) { ?>
            <ul><?php echo $errorMessage ?></ul>
        <?php } ?>
    </ul>
<?php } ?>

</body>
</html>