<?php

namespace uchiko\SQL\Maker;

class Util {
    static public function quoteIdentifier($label, $quote_char, $name_sep) {
        if ( is_string($label) && strcmp($label, '*') === 0 ) {
            return $label;
        }

        if ( ! $name_sep ){
            return $label;
        }

        $new_list = array();
        $list = explode($name_sep, $label);
        foreach ($list as $l) {
            $new_list[] = $quote_char . $l . $quote_char;
        }
        return implode($name_sep, $new_list);
    }

    static public function is_hash($array) {

        $i = 0;
        foreach ($array as $k => $dummy) {
            if ( $k !== $i++ ) return true;
        }
        return false;

    }

    static public function to_array($hash) {

        if ( ! self::is_hash( $hash ) ) { return $hash; }

        $array = array();
        foreach ($hash as $k => $v) {
            $array[] = array($k, $v);
        }

        return $array;

    }

    static public function is_scalar($string) {
        if ( is_object( $string ) ) {
            $class_name = get_class( $string );
            if ( strcmp($class_name, 'uchiko\SQL\Maker\Scalar') === 0 ) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
}

