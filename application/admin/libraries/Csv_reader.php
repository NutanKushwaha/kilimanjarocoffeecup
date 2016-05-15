<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 *https://github.com/bcit-ci/CodeIgniter/wiki/CSVReader 
 * */
class Csv_reader {

    var $fields;/** columns names retrieved after parsing */
    var $separator = '|';/** separator used to explode each line */
    var $enclosure = '"';/** enclosure used to decorate each field */
    var $max_row_size = 4096;/** maximum row size to be used for decoding */

    function parse_file($p_Filepath) {
        $file = fopen($p_Filepath, 'r');
        $this->fields = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure);        
        $keys = $this->fields;
        $i = 1;
        while (($row = fgetcsv($file, $this->max_row_size, $this->separator, $this->enclosure)) != false) {			
            if (count($keys) == count($row)) {
                $arr = array();
                for ($j = 0; $j < count($keys); $j++) {
                    if ($keys[$j] != "") {
                        $arr[$keys[$j]] = $row[$j];
                    }
                }
                $content[$i] = $arr;
                $i++;
            }
            //    }
        }
        fclose($file);
        return $content;
    }

}

?>
