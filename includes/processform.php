<?php
foreach ($_POST as $key => $value) {
    // assign to temporary variable and strip whitespace if not an array
    if (is_array($value)) {
        $temp = $value;
    } else {
        $temp = trim($value);
    }
    // if empty and required, add to $missing array
    if (!is_array($temp)) {
        if (empty($temp) && in_array($key, $required)) {
            $missing[] = $key;
            ${$key} = '';
        } elseif (in_array($key, $expected)) {
            // otherwise, assign to a variable of the same name as $key
            ${$key} = $temp;
        }
    } else {
        foreach ($temp as $key1 => $value1) {
            if (is_array($value1)) {
                $temp1 = $value1;
                foreach ($temp1 as $key2 => $value2) {
                    if (is_array($value2)) {
                        $temp2 = $value2;
                    } else {
                        $temp2 = trim($value2);
                    }
                    if (!is_array($temp2)) {
                        if (empty($value2) && in_array($key, $required)) {
                            if (!in_array($key, $missing)) {
                                $missing[] = $key;
                                break;
                            }
                        }
                    }
                }
            } else {
                $temp1 = trim($value1);
                if (!is_array($temp1)) {
                    if (empty($temp1) && in_array($key, $required)) {
                        $missing[] = $key;
                        break;
                    }
                }
            }
        }
    }
}