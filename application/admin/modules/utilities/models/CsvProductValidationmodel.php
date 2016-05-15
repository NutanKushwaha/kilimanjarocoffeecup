<?php

class CsvProductValidationmodel extends Commonmodel {

    function __construct() {
        parent::__construct();
    }

    public $validErrors = "";
    public $imageTypeAllowed = array('image/png', 'image/jpg', 'image/jpeg');

    function checkEmpty($validation_array = array(), $key, $value) {
        if (!empty($value) && $validation_array[$key]) {
            return true;
        } else {
            $this->validErrors .= $key . " cannot be empty \n";
            return false;
        }
    }

    function imageValidation($path) {
        //if url is a full url with image
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            if ($this->validImage($path, 'full')) {
                //image ready to upload here
                return true;
            } else {
                $this->validErrors .= "path is not a valid image \n";
                return false;
            }
        } else {

            if (file_exists($this->config->item('IMPORT_IMAGE_PATH') . $path)) {
                //var_dump($this->validImage($path, 'half'));
                if ($this->validImage($path, 'half')) {
                    //image ready to upload here
                    return true;
                } else {
                    $this->validErrors .= "path is not a valid image \n";
                    return false;
                }
            } else {
                $this->validErrors = "path is not a valid image. \n";
                return false;
            }
        }
    }

    function validImage($path, $mode = 'half') {

        switch ($mode) {
            case 'full':
                $fileDetails = get_headers($path, 1);
                if (in_array($fileDetails['Content-Type'], $this->imageTypeAllowed)) {
                    return true;
                } else {
                    return false;
                }
                break;
            case "half":
                $result = new finfo();
                $contentType = $result->file($this->config->item('IMPORT_IMAGE_PATH') . $path, FILEINFO_MIME_TYPE);
                if (in_array($contentType, $this->imageTypeAllowed)) {
                    return true;
                } else {
                    return false;
                }
        }
    }

    function __destruct() {
        $this->validErrors = "";
    }

}
