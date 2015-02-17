<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validator extends CI_Controller {

    public function index() {
        redirect("validator/validate");
    }

    public function project() {
        $projectsDir = FCPATH . "upload/";

        $projectsList = [];

        if ($projectsDirHandle = opendir($projectsDir)) {
            while (false !== ($entry = readdir($projectsDirHandle))) {
                if ($entry != "." && $entry != "..") {
                    $projectsList[] = $entry;
                }
            }
            closedir($projectsDirHandle);
        }

        $this->load->view('select_project_view', ["projects" => $projectsList]);

    }

    public function validate($project = "test") {
        require_once APPPATH . 'third_party/PHPExcel/PHPExcel.php';

        $projectPath = FCPATH . "upload/" . $project . "/";

        // Todo Scan project path for a file with extension .xlsx
        $excelFile = $projectPath . "test.xlsx";

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($excelFile);

        // Iterating through all the sheets in the excel workbook and storing the array data
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $arrayData[$worksheet->getTitle()] = $worksheet->toArray();
        }

        $cleanedData = [];
        $resCodeLabel = "";
        $resCategoryLabel = "";
        $resTitleLabel = "";

        foreach ($arrayData['CLA Media Grid'] as $row_number => $entry) {
            if($row_number == 0) {
                $resCodeLabel = $entry[0];
                $resCategoryLabel = $entry[4];
                $resTitleLabel = $entry[6];
            }
            else {
                $cleanedData[] = [
                    $resCodeLabel => $entry[0],
                    $resCategoryLabel => $entry[4],
                    $resTitleLabel => $entry[6]
                ];
            }
        }

        $data = [
            "excel" => $cleanedData
        ];

        $this->load->view('validate_results_view', $data);
    }
}
