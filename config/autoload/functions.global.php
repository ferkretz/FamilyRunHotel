<?php

if (!function_exists('mb_ucfirst')) {

    function mb_ucfirst($str,
                        $encoding = "UTF-8") {
        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
                mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
    }

}

return [];
