<?php
function read_excel()
{
    if (isset($_FILES["file_import"]["tmp_name"])) {
        $path = $_FILES['file_import']['name'];
        $ext = PATHINFO($path, PATHINFO_EXTENSION);
        if (in_array($ext, ['xls', 'xlsx', 'csv'])) {
            return $_FILES["file_import"]['tmp_name'];
        } else {
            echo 'file error';
            die;
        }
    }
}

function import_excel($headers = [], &$save_all, &$message, $first = false)
{

    $CI = &get_instance();

    ini_set('memory_limit', '4048M');
    set_time_limit(0);
    error_reporting(E_ALL);

    $inputFileName = read_excel();

    $save_all = [];
    $message = '';
    $CI->load->library("PHPExcel");

    //  Read your Excel workbook
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);

    //  Get worksheet dimensions
    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

    //  Loop through each row of the worksheet in turn
    $start = $first ? 2 : 3;
    for ($row = $start; $row <= $highestRow; $row++) {
        //  Read a row of data into an array
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
        $rowCell = $rowData[0];

        $items = [];
        $error = '';
        foreach ($rowCell as $key => $val) {
            if (isset($headers[$key]['label']) && $rowCell[0] != "") {
                $label = $headers[$key]['label'];
                $name = $headers[$key]['name'];
                if (isset($headers[$key]['type']) && $headers[$key]['type'] != 'ignore') {
                    if (isset($headers[$key]['field']) && isset($headers[$key]['table'])) {
                        $field = $headers[$key]['field'];
                        $table = $headers[$key]['table'];
                        $name_as = empty(@$headers[$key]['name_as']) ? $headers[$key]['name'] : $headers[$key]['name_as'];
                        $field_as = empty(@$headers[$key]['field_as']) ? $headers[$key]['field'] : $headers[$key]['field_as'];
                        $field_relate = isset($headers[$key]['field_relate']) ? $headers[$key]['field_relate'] : '';
                        if (!empty($field_relate)) {
                            $CI->db->where($field_relate, @$items[$field_relate]);
                        }
                        $rec = $CI->db
                            ->where('sys_status', 'active')
                            ->where($name_as, $val)
                            ->get($table)
                            ->first_row('array');
                        if (isset($rec[$field_as])) {
                            $items[$field] = $rec[$field_as];
                        } else {
                            if ($val == "" && isset($headers[$key]['required']) && $headers[$key]['required'] == false) {
                                $items[$field] = null;
                            } else {
                                $error .= '<p>ไม่พบข้อมูล ' . $label . ' แถวที่ ' . ($row) . '</p>';
                            }
                        }
                    } else if (isset($headers[$key]['required'])) {
                        if (trim($val) === '' || is_null($val)) {
                            $error .= '<p>ไม่พบข้อมูล ' . $label . ' แถวที่ ' . ($row) . '</p>';
                        } else {
                            $items[$name] = $val;
                        }
                    } else if ($headers[$key]['type'] === 'money') {
                        $items[$name] = format_money_import($val);
                    } else if (isset($headers[$key]['allowData'])) {
                        if (!in_array($val, $headers[$key]['allowData'])) {
                            $error .= '<p>ข้อมูลไม่ถูกต้อง ' . $label . ' [' . $val . '] แถวที่ ' . ($row) . '</p>';
                        } else {
                            $items[$name] = $val;
                        }
                    } else if (isset($headers[$key]['checkDate']) && $headers[$key]['checkDate']) {
                        if (!check_date_import($val)) {
                            $error .= '<p>ข้อมูลไม่ถูกต้อง ' . $label . ' [' . $val . '] แถวที่ ' . ($row) . '</p>';
                        } else {
                            $items[$name] = $val;
                        }
                    } else if (isset($headers[$key]['checkMonthly']) && $headers[$key]['checkMonthly']) {
                        if (!check_monthly_import($val)) {
                            $error .= '<p>ข้อมูลไม่ถูกต้อง ' . $label . ' [' . $val . '] แถวที่ ' . ($row) . '</p>';
                        } else {
                            $items[$name] = format_monthly_import($val);
                        }
                    } else {
                        $items[$name] = is_null($val) ? '' : $val;
                    }
                }
            } else {
                break;
            }
        }
        if ($error != '') $message .= $error . '<hr />';
        if (count($items) > 0) array_push($save_all, $items);
    }

    $maxRow = 10000;
    $heightRow = count($save_all);
    if ($heightRow > $maxRow) {
        $message = '<p>กรุณานำเข้าครั้งละไม่เกิน ' . $maxRow . ' รายการ</p>';
    }

    if ($message == '') {
        $message = $heightRow . ' item(s) ';
        return true;
    } else {
        return false;
    }
}

function export_excel($title = '', $filename = '', $headers = [], $results = [], $locked = false, $first = false)
{
    $CI = &get_instance();

    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '-Export-' . date("YmdHis") . '.xlsx"');
    ini_set('memory_limit', '4048M');
    set_time_limit(0);
    error_reporting(E_ALL);
    $CI->load->library("PHPExcel");

    $cols = array();
    $char = 'A';
    foreach ($headers as $val) {
        $cols[$char++] = $val;
    }

    $lastCol = $char;

    $row   = 1;
    $new_arr = array_keys($cols);
    $col_s = reset($new_arr);
    $col_e = end($new_arr);
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->createSheet(0);
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle($filename . " Export");
    if ($locked) $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);

    $styleArray =  array();
    $styleArray['font'] = array('bold'  => true);
    $styleArray['alignment']['vertical'] = PHPExcel_Style_Alignment::VERTICAL_CENTER;

    if (!$first) {
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $title . " Export " . date("d/m/Y"));
        $objPHPExcel->getActiveSheet()->mergeCells($col_s . $row . ":" . $col_e . $row);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(40);

        $objPHPExcel->getActiveSheet()->protectCells($col_s . $row . ":" . $col_e . $row, 'PHPExcel');
        $objPHPExcel->getActiveSheet()->getStyle($col_s . $row . ":" . $col_e . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);

        $row++;
    }

    foreach ($cols as $k_col => $v_col) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($k_col)->setWidth($v_col['width']);
        $objPHPExcel->getActiveSheet()->SetCellValue($k_col . $row, $v_col['label']);
        $styleArray =  array();
        $styleArray['fill'] = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFE994'));
        $styleArray['alignment'] = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $styleArray['borders']  = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
        $objPHPExcel->getActiveSheet()->getStyle($k_col . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle($k_col . $row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
    }
    $objPHPExcel->getActiveSheet()->getStyle($lastCol . $row . ':Z' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

    if (isset($headers[0]['sub_label'])) {
        $row++;
        $sub_cols = array();
        $char = 'A';
        foreach ($headers as $val) {
            $sub_cols[$char++] = $val;
        }
        foreach ($sub_cols as $k_col => $v_col) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($k_col)->setWidth($v_col['width']);
            $objPHPExcel->getActiveSheet()->SetCellValue($k_col . $row, $v_col['sub_label']);
            $styleArray =  array();
            $styleArray['fill'] = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFE994'));
            $styleArray['alignment'] = array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $styleArray['borders']  = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
            $objPHPExcel->getActiveSheet()->getStyle($k_col . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $objPHPExcel->getActiveSheet()->getStyle($k_col . $row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
        }
    }

    foreach ($results as $key => $val) {
        $row++;
        foreach ($cols as $k_col => $v_col) {
            if (isset($v_col['type']) && ($v_col['type'] == "string" || $v_col['type'] == "translate")) {
                $objPHPExcel->getActiveSheet()->getStyle($k_col . $row, $val[$v_col['name']])->getNumberFormat()->setFormatCode('0');
            }
            if (isset($v_col['type']) && $v_col['type'] == "date") {
                $date = ($val[$v_col['name']] == "") ? "" : date("d/m/Y", strtotime($val[$v_col['name']]));
                $objPHPExcel->getActiveSheet()->SetCellValue($k_col . $row, $date);
            } else if (isset($v_col['type']) && $v_col['type'] == "datetime") {
                $date = ($val[$v_col['name']] == "") ? "" : date("d/m/Y H:i", strtotime($val[$v_col['name']]));
                $objPHPExcel->getActiveSheet()->SetCellValue($k_col . $row, $date);
            } else if (isset($v_col['type']) && $v_col['type'] == "duration") {
                $date = ($val[$v_col['name']] == "") ? "" :  convert_duration_time($val[$v_col['name']]);
                $objPHPExcel->getActiveSheet()->SetCellValue($k_col . $row, $date);
            } else if (isset($v_col['type']) && $v_col['type'] == "monthly") {
                $month = $val[$v_col['month']];
                $year = $val[$v_col['year']];
                $monthly = format_monthly_export($month, $year);
                $objPHPExcel->getActiveSheet()->SetCellValue($k_col . $row, $monthly);
            } else if (isset($v_col['type']) && $v_col['type'] == "money") {
                $money = ($val[$v_col['name']] == "") ? '0.00' : format_money_export($val[$v_col['name']]);
                $objPHPExcel->getActiveSheet()->SetCellValue($k_col . $row, $money);
            } else {
                if (isset($v_col['type']) && $v_col['type'] == "auto") {
                    $objPHPExcel->getActiveSheet()->SetCellValue($k_col . $row, ($key + 1));
                } else if (isset($v_col['name'])) {
                    if ($v_col['type'] == "translate") {
                        $name = t($val[$v_col['name']]);
                    } else if ($v_col['type'] == "url") {
                        $name = $val[$v_col['name']];
                        if ($name != "") $name = '=HYPERLINK("' . base_url("upload/" . $v_col['module'] . "/" . $name) . '", "คลิกเพื่อดู")';
                    } else {
                        $name = $val[$v_col['name']];
                    }
                    $objPHPExcel->getActiveSheet()->SetCellValue($k_col . $row, $name);
                } else {
                    $objPHPExcel->getActiveSheet()->SetCellValue($k_col . $row, "");
                }
            }

            $objPHPExcel->getActiveSheet()->getStyle($k_col . $row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            $styleArray = array();
            if (isset($v_col['color'])) {
                $styleArray['fill'] = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => $v_col['color']));
            }

            if (isset($v_col['align'])) {
                switch ($v_col['align']) {
                    case 'center':
                        $styleArray['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
                        break;
                    case 'right':
                        $styleArray['alignment']['horizontal']  = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
                        break;
                    case 'left':
                        $styleArray['alignment']['horizontal']   = PHPExcel_Style_Alignment::HORIZONTAL_LEFT;
                        break;
                }
            }

            if (isset($v_col['valign'])) {
                switch ($v_col['valign']) {
                    case 'middle':
                        $styleArray['alignment']['vertical'] = PHPExcel_Style_Alignment::VERTICAL_CENTER;
                        break;
                    case 'top':
                        $styleArray['alignment']['vertical']    = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
                        break;
                    case 'bottom':
                        $styleArray['alignment']['vertical'] = PHPExcel_Style_Alignment::VERTICAL_BOTTOM;
                        break;
                }
            }

            $styleArray['borders']  = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
            $objPHPExcel->getActiveSheet()->getStyle($k_col . $row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
            if (isset($v_col['unlock'])) $objPHPExcel->getActiveSheet()->getStyle($k_col . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        }
        $objPHPExcel->getActiveSheet()->getStyle($lastCol . $row . ':Z' . $row)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
    }

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save('php://output');
}

function export_csv($title = '', $filename = '', $headers = [], $results = [], $show_title = false)
{
    ob_start();
    $csv = fopen("php://output", 'w');

    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '-Export-' . date("YmdHis") . '.xlsx"');
    ini_set('memory_limit', '4048M');
    set_time_limit(0);
    error_reporting(E_ALL);

    $CI = &get_instance();
    $CI->load->library("PHPExcel");

    $cols = [];
    $data = [];
    $char = 'A';

    foreach ($headers as $val) {
        $cols[$char++] = $val;
        array_push($data, $val['label']);
    }

    fputs($csv, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
    if ($show_title) {
        fputcsv($csv, [$title . " Export " . date("d/m/Y")]);
    }

    fputcsv($csv, $data);

    if (isset($headers[0]['sub_label'])) {
        $data = [];
        foreach ($headers as $val) {
            array_push($data, $val['sub_label']);
        }
        fputcsv($csv, $data);
    }

    foreach ($results as $key => $val) {
        $data = [];
        foreach ($cols as $k_col => $v_col) {
            $field = "";
            if (isset($v_col['type']) && $v_col['type'] == "date") {
                $date = ($val[$v_col['name']] == "") ? "" : date("d/m/Y", strtotime($val[$v_col['name']]));
                $field = $date;
            } else if (isset($v_col['type']) && $v_col['type'] == "datetime") {
                $date = ($val[$v_col['name']] == "") ? "" : date("d/m/Y H:i", strtotime($val[$v_col['name']]));
                $field = $date;
            } else if (isset($v_col['type']) && $v_col['type'] == "duration") {
                $date = ($val[$v_col['name']] == "") ? "" : convert_duration_time($val[$v_col['name']]);
                $field = $date;
            } else if (isset($v_col['type']) && $v_col['type'] == "monthly") {
                $month = $val[$v_col['month']];
                $year = $val[$v_col['year']];
                $field = format_monthly_export($month, $year);
            } else if (isset($v_col['type']) && $v_col['type'] == "money") {
                $money = ($val[$v_col['name']] == "") ? '0.00' : format_money_export($val[$v_col['name']]);
                $field = $money;
            } else if (isset($v_col['type']) && $v_col['type'] == "numberString") {
                $field = '="' . $val[$v_col['name']] . '"';
            } else {
                if (isset($v_col['type']) && $v_col['type'] == "auto") {
                    $field = ($key + 1);
                } else if (isset($v_col['name'])) {
                    if ($v_col['type'] == "translate") {
                        $field = t($val[$v_col['name']]);
                    } else if ($v_col['type'] == "url") {
                        $field = $val[$v_col['name']];
                        if ($field != "") $field = '=HYPERLINK("' . base_url("upload/" . $v_col['module'] . "/" . $field) . '", "คลิกเพื่อดู")';
                    } else {
                        $field = $val[$v_col['name']];
                    }
                } else {
                    $field = "";
                }
            }
            $field = $field === "" ? NULL : (string)$field;
            array_push($data, $field);
        }
        fputcsv($csv, $data);
    }

    fclose($csv);
    $csvContent = ob_get_clean();

    $tmpCsv = tempnam(sys_get_temp_dir(), 'csv');
    file_put_contents($tmpCsv, $csvContent);

    $objReader = new PHPExcel_Reader_CSV();
    $objReader->setDelimiter(',');
    $objReader->setEnclosure('"');
    $objReader->setSheetIndex(0);

    $objPHPExcel = $objReader->load($tmpCsv);
    $objPHPExcel->getActiveSheet()->setTitle($filename . " Export");

    foreach ($cols as $k_col => $v_col) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($k_col)->setWidth($v_col['width']);
    }

    $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();
    for ($i = 1; $i <= $highestRow; $i++) {
        $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    unlink($tmpCsv);
}


function export_preview($title = '', $filename = '', $headers = [], $results = [])
{
    set_time_limit(0);
    error_reporting(E_ALL);

    $cols = [];
    $data = [];
    $char = 'A';

    foreach ($headers as $val) {
        $cols[$char++] = $val;
        array_push($data, $val['label']);
    }

    $html = '<div style="margin-bottom:10px;">' . $title . " Export " . date("d/m/Y") . '</div>';
    $html .= '<table border="1" style="border-spacing: 0px;border-collapse: collapse;width: 100%;">';
    $html .= '<tr>';
    foreach ($data as $val) {
        $html .= '<th style="padding: 5px 5px;">' . $val . '</th>';
    }
    $html .= '</tr>';

    if (isset($headers[0]['sub_label'])) {
        $html .= '<tr>';
        foreach ($headers as $val) {
            $html .= '<th style="padding: 5px 5px;">' . $val['sub_label'] . '</th>';
        }
        $html .= '</tr>';
    }

    foreach ($results as $key => $val) {
        $data = [];
        foreach ($cols as $k_col => $v_col) {
            $field = "";
            if (isset($v_col['type']) && $v_col['type'] == "date") {
                $date = ($val[$v_col['name']] == "") ? "" : date("d/m/Y", strtotime($val[$v_col['name']]));
                $field = $date;
            } else if (isset($v_col['type']) && $v_col['type'] == "datetime") {
                $date = ($val[$v_col['name']] == "") ? "" : date("d/m/Y H:i", strtotime($val[$v_col['name']]));
                $field = $date;
            } else if (isset($v_col['type']) && $v_col['type'] == "duration") {
                $date = ($val[$v_col['name']] == "") ? "" : convert_duration_time($val[$v_col['name']]);
                $field = $date;
            } else if (isset($v_col['type']) && $v_col['type'] == "monthly") {
                $month = $val[$v_col['month']];
                $year = $val[$v_col['year']];
                $field = format_monthly_export($month, $year);
            } else if (isset($v_col['type']) && $v_col['type'] == "money") {
                $money = ($val[$v_col['name']] == "") ? '0.00' : format_money_export($val[$v_col['name']]);
                $field = $money;
            } else {
                if (isset($v_col['type']) && $v_col['type'] == "auto") {
                    $field = ($key + 1);
                } else if (isset($v_col['name'])) {
                    if ($v_col['type'] == "translate") {
                        $field = t($val[$v_col['name']]);
                    } else if ($v_col['type'] == "url") {
                        $field = $val[$v_col['name']];
                        if ($field != "") $field = '=HYPERLINK("' . base_url("upload/" . $v_col['module'] . "/" . $field) . '", "คลิกเพื่อดู")';
                    } else {
                        $field = $val[$v_col['name']];
                    }
                } else {
                    $field = "";
                }
            }
            $field = $field === "" ? NULL : (string)$field;
            array_push($data, $field);
        }
        $html .= '<tr>';
        foreach ($data as $key => $val) {
            $val  = $val == "" ?  "&nbsp;" : $val;
            $html .= '<td style="padding: 5px 5px;">' . $val . '</td>';
        }
        $html .= '</tr>';
    }

    $html .= '</table>';
    echo $html;
}
