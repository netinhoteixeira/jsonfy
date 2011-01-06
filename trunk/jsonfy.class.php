<?php

/*
 * jsonfy.class.php
 *
 * Create on 2011-01-04, 16:26
 *
 * @author Francisco Ernesto Teixeira <contato@netinho.info>
 */

/**
 * Class JSONfy
 */
class JSONfy {

    /**
     * Instance of this class.
     *
     * @var JSONfy
     */
    protected static $instance;
    /**
     * Parameter Callback.
     *
     * @var bool
     */
    private $callback;

    /**
     * Constructor.
     */
    function __construct() {
        $this->callback = isset($_GET['callback']) ? $_GET['callback'] : false;
    }

    /**
     * Get or create a instance object of current class.
     *
     * @return JSONfy
     */
    final public static function getInstance() {
        if (self::$instance === NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Verify if this class is intancied by singleton.
     *
     * @return bool
     */
    final public static function isInstancied() {
        return (bool) self::$instance;
    }

    /**
     * Verify if exits the parameter callback.
     *
     * @return bool
     */
    function hasCallback() {
        return (bool) $this->callback;
    }

    /**
     * Convert array to javascript object/array.
     * 
     * @param array $array the array
     * @return string
     */
    public static function encode($array) {
        if (version_compare(PHP_VERSION, '5.2.0', '<')) {
            // determine type
            if (is_numeric(key($array))) {
                // indexed (list)
                $output = '[';
                for ($i = 0, $last = (sizeof($array) - 1); isset($array[$i]); ++$i) {
                    if (is_array($array[$i]))
                        $output .= self::encode($array[$i]);
                    else
                        $output .= self::_val($array[$i]);
                    if ($i !== $last)
                        $output .= ',';
                }
                $output .= ']';
            } else {
                // associative (object)
                $output = '{';
                $last = sizeof($array) - 1;
                $i = 0;
                foreach ($array as $key => $value) {
                    $output .= '"' . $key . '":';
                    if (is_array($value)) {
                        $output .= self::encode($value);
                    } else {
                        $output .= self::_val($value);
                    }

                    if ($i !== $last) {
                        $output .= ',';
                    }
                    ++$i;
                }
                $output .= '}';
            }

            return $output;
        } else {
            return json_encode($array);
        }
    }

    /**
     * Format value.
     *
     * @param mixed $val the value
     * @return string
     */
    private static function _val($val) {
        if (is_string($val)) {
            return '"' . rawurlencode($val) . '"';
        } elseif (is_int($val)) {
            return sprintf('%d', $val);
        } elseif (is_float($val)) {
            return sprintf('%F', $val);
        } elseif (is_bool($val)) {
            return ($val ? 'true' : 'false');
        } else {
            return 'null';
        }
    }

    /**
     * Proccess a array and converts and print to JSON format with callback.
     *
     * @param array $arrayrray
     */
    function show($array) {
        if ($this->hasCallback()) {
            echo $this->callback . '(' . self::encode($array) . ')';
        } else {
            throw new Exception('The URL request needs the parameter $_GET[\'callback\'].');
        }
    }

}

?>