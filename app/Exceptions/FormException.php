<?php
/**
 * Created by PhpStorm.
 * User: WILLIAM
 * Date: 19/08/2017
 * Time: 21:12
 */

namespace App\Exceptions;
use Exception;

class FormException extends Exception {
    public function errorMessage() {
        //error message
        $errorMsg = $this->getMessage();
        return $errorMsg;
    }
}