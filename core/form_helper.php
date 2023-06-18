<?php

class FormHelper {

    public static function open($action, $method = 'post', $options = '', $multipart = FALSE) {
        if ($multipart !== FALSE) {
            $multipart = "enctype=\"multipart/form-data\"";
        }
        
        $action = BASE_PATH . $action;

        return "<form method=\"$method\" action=\" $action\" $options $multipart >" . PHP_EOL . static::hidden('safetykey', md5(rand()) . chr(rand(65, 90)) . md5(SAFETY_SEED) . chr(rand(48, 57)));
    }

    public static function openMultipart($action, $method = 'post', $options = '') {
        $multipart = "enctype=\"multipart/form-data\"";
        $action = BASE_PATH . $action;

        return "<form method=\"$method\" action=\"$action\" $options $multipart >" . PHP_EOL . static::hidden('safetykey', md5(rand()) . chr(rand(65, 90)) . md5(SAFETY_SEED) . chr(rand(48, 57)));
    }

    public static function close() {
        return "</form>";
    }

    public static function text($field, $value = null, $options = '') {
        $inputId = $field;
        $inputName = $field;
        if (strpos($field, '.') !== FALSE) {
            $elements = explode('.', $field);
            $inputId = $elements [0] . '_' . $elements [1];
            $inputName = $elements [0] . '[' . $elements [1] . ']';
        }

        if ($value != null) {
            $value = "value=\"$value\"";
        }

        return "<input type=\"text\" id=\"$inputId\" name=\"$inputName\" $options $value />";
    }

    public static function password($field, $value = null, $options = '') {
        $inputId = $field;
        $inputName = $field;
        if (strpos($field, '.') !== FALSE) {
            $elements = explode('.', $field);
            $inputId = $elements [0] . '_' . $elements [1];
            $inputName = $elements [0] . '[' . $elements [1] . ']';
        }
        if ($value != null) {
            $value = "value=\"$value\"";
        }

        return "<input type=\"password\" id=\"$inputId\" name=\"$inputName\" $options $value />";
    }

    public static function number($field, $value = null, $options = '') {
        $inputId = $field;
        $inputName = $field;
        if (strpos($field, '.') !== FALSE) {
            $elements = explode('.', $field);
            $inputId = $elements [0] . '_' . $elements [1];
            $inputName = $elements [0] . '[' . $elements [1] . ']';
        }
        if ($value != null) {
            $value = "value=\"$value\"";
        }

        return "<input type=\"number\" step=\"1\" min=\"0\" id=\"$inputId\" name=\"$inputName\" $options $value />";
    }

    public static function textarea($field, $value = null, $options = '') {
        $inputId = $field;
        $inputName = $field;
        if (strpos($field, '.') !== FALSE) {
            $elements = explode('.', $field);
            $inputId = $elements [0] . '_' . $elements [1];
            $inputName = $elements [0] . '[' . $elements [1] . ']';
        }
        return "<textarea id=\"$inputId\" name=\"$inputName\" $options>$value</textarea>";
    }

    public static function check($field, $value, $checked = FALSE, $options = '') {
        $inputId = $field;
        $inputName = $field;
        if (strpos($field, '.') !== FALSE) {
            $elements = explode('.', $field);
            $inputId = $elements [0] . '_' . $elements [1];
            $inputName = $elements [0] . '[' . $elements [1] . ']';
        }
        if ($checked == TRUE) {
            $checked = "checked=\"checked\"";
        } else {
            $checked = '';
        }
        return "<input type=\"checkbox\" id=\"$inputId\" name=\"$inputName\" $checked $options value=\"$value\" />";
    }

    public static function select($field, $data, $value = null, $options = '') {
        $inputId = $field;
        $inputName = $field;
        if (strpos($field, '.') !== FALSE) {
            $elements = explode('.', $field);
            $inputId = $elements [0] . '_' . $elements [1];
            $inputName = $elements [0] . '[' . $elements [1] . ']';
        }

        $result = "<select id=\"$inputId\" name=\"$inputName\" $options>" . PHP_EOL;
        foreach ($data as $key => $show) :
            $selected = '';
            if (strcmp($key, $value) == 0 && isset($value)) {
                $selected = "selected=\"selected\"";
            }
            $result .= "<option value=\"$key\" $selected>" . $show . "</option>" . PHP_EOL;
        endforeach
        ;
        $result .= "</select>" . PHP_EOL;

        return $result;
    }

    public static function dbSelect($field, $show, $data, $value = null, $options = '') {
        $elements = [];
        foreach ($data as $elem) :
            $elements [$elem ['id']] = $elem [$show];
        endforeach
        ;
        return static::select($field, $elements, $value, $options);
    }

    public static function date($field, $value = null, $options = '') {
        $inputId = $field;
        $inputName = $field;
        if (strpos($field, '.') !== FALSE) {
            $elements = explode('.', $field);
            $inputId = $elements [0] . '_' . $elements [1];
            $inputName = $elements [0] . '[' . $elements [1] . ']';
        }
        if ($value != null) {
            $value = "value=\"$value\"";
        }
        return "<input type=\"date\" id=\"$inputId\" name=\"$inputName\" $options $value />";
    }

    public static function label($field, $text, $options = '') {
        $inputName = $field;
        if (strpos($field, '.') !== FALSE) {
            $elements = explode('.', $field);
            $inputName = $elements [0] . '[' . $elements [1] . ']';
        }
        return "<label for=\"$inputName\" $options>$text</label>";
    }

    public static function hidden($field, $value = null) {
        $inputId = $field;
        $inputName = $field;
        if (strpos($field, '.') !== FALSE) {
            $elements = explode('.', $field);
            $inputId = $elements [0] . '_' . $elements [1];
            $inputName = $elements [0] . '[' . $elements [1] . ']';
        }
        if ($value != null) {
            $value = "value=\"$value\"";
        }
        return "<input type=\"hidden\" id=\"$inputId\" name=\"$inputName\" $value />";
    }

    public static function button($value, $type = 'button', $options = '') {
        return "<button type=\"$type\" $options>$value</button>";
    }

    public static function submit($value, $options = '') {
        return "<button type=\"submit\" $options>$value</button>";
    }

    public static function selectedValue($value = '', $compareWith = '') {
        if (strcmp($value, $compareWith) === 0) :
            return 'selected = "selected"';
        else :
            return '';
        endif;
    }

    public static function getFieldName($field = '') {
        if (strpos($field, '.') !== FALSE) :
            $elements = explode('.', $field);
            if (count($elements) === 2) :
                return [
                    "{$elements[0]}_{$elements[1]}",
                    "{$elements[0]}[{$elements[1]}]"
                ];
            else :

            endif;
        else :
            return [
                $field,
                $field
            ];
        endif;
    }

}
