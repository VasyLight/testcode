<?php 


class Logger {
    public function __construct(
        public string $msgType, public string $msg ) {
    }
}

class CsvToXml {
    public array $allIdsFromCsv;
    public array $headers;

    public function generateLog($msgType, $msg) {
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
}