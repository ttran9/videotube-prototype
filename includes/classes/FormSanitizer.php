<?php
class FormSanitizer {

    public static function sanitizeFormString($inputText) {
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ", "", $inputText);
//        $inputText = trim($inputText); // cuts off spaces at the beginning and end (could enter '      Reece Tom      '  )
        $inputText = strtolower($inputText);
        $inputText = ucfirst($inputText);
        return $inputText;
    }

}
?>