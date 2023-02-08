<?php

$headers = [];
// Create a new dom document
$doc = new DOMDocument;


/**
 * logs in file 
 * @param mixed $msgType
 * @param mixed $msg
 * @return void
 */
function generateLog($msgType, $msg) {
    # directory for current date to get it

    $log_filename = './logs' . date('d-M-Y');

    # if there is no directory create one

    if(!is_dir($log_filename)) {
        mkdir($log_filename, 0777, true);
    }

    # create a log file name

    $log_file_data = $log_filename . '/log_' . date('d-M-Y') . '.log';

    # create a log file and append messages to it

    file_put_contents($log_file_data, $msgType . ': ' . date('d-m-Y h:i:s') . ':' . $msg . "\n" . PHP_EOL, FILE_APPEND | LOCK_EX);
}


 /**
  * Funtion to break lagre csv into smaller csv's
  * @param mixed $chunkSize
  * @param mixed $path
  * @return bool
  */
 function getDataFromCsvAndGenerateSmallerCsv($chunkSize, $path) {

    $csvFile = file($path);
    $output = null;

    global $headers;
    # Save headers to global variable as array
    $headers = explode(";", $csvFile[0]);

    # Removing headers
    unset($csvFile[0]);

    foreach(array_chunk($csvFile, $chunkSize) as $counter =>$eachChunk) {

        set_time_limit(30);
        $output = ++$counter;
        exportToCsvSmallerCSV($eachChunk, $counter);
        echo $output . " Chunk csv's complited"."</br>";
    }
    
    return true;
 }

 /**
  * Function to generate smaller csv files
  * @param mixed $chunkData
  * @param mixed $counter
  * @return void
  */
 function exportToCsvSmallerCSV($chunkData, $counter) {
    # Preparing data for csv

    $dataNew = [];

    foreach($chunkData as $csvString) {
        if(!in_array($csvString, $dataNew, true)) {

            $dataNew[] = $csvString;
        }
    }

    $fileDirectiory = './files';

    # if there is no directory create one

    if(!is_dir($fileDirectiory)) {
        mkdir($fileDirectiory, 0777, true);
    }

    $todayDate = date('Y-m-d h-i-s');
    $fileName = 'files/Xml file-'.$counter++.'_on_'.$todayDate.'.csv';

    generateLog('info', 'Generated: '. $fileName .', File:'. __FILE__ . 'Line:' . __LINE__);

    $filePath = $fileName;

    $fp = fopen($filePath, 'w+');

    foreach($dataNew as $line) {

        $val = explode(";", $line); # TODO Вынести разделитель в инициализацию функции

        fputcsv($fp, $val);
    }
    fclose($fp);
 }

 function getDataFromCsvAndSave($chunkSize) {

    global $doc;
    
    $path = 'files';

    $files = scandir($path);

    # loop through each file

    foreach($files as $file) {
       
        if($file !='.' && $file !='..' && $file !='.DS_Store' && file_exists($path .'/'. $file)) {
            set_time_limit(30);

            readCsvAndSaveToXml($path . '/' . $file, $chunkSize);
        }
    }


    $todayDate = date('Y-m-d h-i-s');

    $fileName = 'files/Xml file on_'.$todayDate.'.xml';

    if (($handle = fopen($fileName, "w")) != FALSE) {

        $strxml = $doc->saveXML();
        fwrite($handle, $strxml);
        fclose($handle);

        generateLog('info', 'Generated: '. $fileName .', File:'. __FILE__ . 'Line:' . __LINE__);

        echo 'Success! File: '. $fileName . " was created"."</br>";
    }

    

 }

 function readCsvAndSaveToXml($filename, $chunkSize) {

    global $headers;
    global $doc;

    if (($handle = fopen($filename, "r")) != FALSE) {

        

        while($data = fgetcsv($handle, $chunkSize, ",")) {

            # Each time insert some amount to global doc

            $container = $doc->createElement('item');
            # Take global headers
            
            foreach($headers as $i => $header)
            {
                if (isset($header)) {
                    $clean_header = str_replace(array("\r", "\n"), "", $header);

                    $child = $doc->createElement(htmlentities($clean_header, ENT_XML1, 'UTF-8'));
                    $child = $container->appendChild($child);
                }

                if (isset($data[$i])) {
                    $clean_data = str_replace(array("\r", "\n"), "", $data[$i]);

                    $value = $doc->createTextNode(htmlentities($clean_data, ENT_XML1, 'UTF-8'));
                    $value = $child->appendChild($value);
                }
                


                
                }

            $doc->appendChild($container);

        }

        # Delete unneded csv's
        if (file_exists($filename)) {
            unlink($filename);
        }

    }

 }
