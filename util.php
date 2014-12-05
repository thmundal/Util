<?php
/**
 * Handy utility functions.
 * @author Thomas Mundal <thmundal@gmail.com>
 */

/**
 * Displays or returns a datastructure printed out in HTML pre tags
 * for easier readability.
 * @param mixed $data
 * Contains the data to be printed
 * @param bool $return
 * Determens if the data is to be echoed or returned for later use
 * @return string
 * Returns the pre-formatted string, or NULL if $return is false
 */
function pre_print_r($data, $return = false) {
    $pre = "<pre>" . print_r($data, true) . "</pre>";
    
    if($return)
        return $pre;
    
    echo $pre;
    return null;
}

/**
 * Get the value of an array at the given index, if the index is not found the return value
 * is based upon the given $return_value
 * @param array $array
 * The array from which the value is to be gotten.
 * @param mixed $key
 * The key to the value to be gotten.
 * @param mixed $return_value
 * Default value is "%throw-exception%" and the function will throw an exception if the key
 * could not be found in the array and no return value is specified.
 * @return mixed
 * Returns the data at the key in the given array
 * @throws Exception
 * Exception is thrown if the given key does not exist in the array, and when a return value is not 
 * specified
 */
function arrGet(Array $array, $key, $return_value = "%throw-exception%") {
    if(!array_key_exists($key, $array))
        if($return_value == "%throw-exception%")
            throw new Exception("Could not find key ".$key." in array: " . pre_print_r($array, true));
        else
            return $return_value;
            
    return $array[$key];
}

/**
 * Converts a string to an integer value
 * @param string $input
 * @return int
 * @throws Exception
 * Throws an exception if the input value is not a string.
 */
function toInt($input) {
    if(!is_string($input))
        throw new Exception("Cannot convert input to integer, string expected - got ".gettype($input));
    return (float) $input;
}

/**
 * Converts an array to string
 * @param array $input
 * @return string
 */
function arrayToString(Array $input) {
    $output = "";
    foreach($input as $key) {
        $output .= $key. " ";
    }
    return $output;
}

/**
 * Alias function to call_user_func
 * @param Callable $callback
 * @return type
 */
function call(Callable $callback) {
    return call_user_func($callback);
}

/**
 * Encapsulates the given string in quotation marks
 * @param  string       The string to quote
 * @param  string       Optional quotation marks
 * @return string       Returns the quoted string
 */
function quote($string, $quote = "'") {
    assertType($string, "string");
    return $quote.$string.$quote;
}

/**
 * Redirects the request to given url by the given method
 * @param  string $url      The URL to redirect to
 * @param  string $method   The method of redirection, can be "header" or "meta"
 */
function redirect($url, $method="header") {
    if($method == "header") {
        header("location: ".$url);
    } elseif($method == "meta") {
        echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
    } else {
        throw new Exception("No valid method provided");
    }
}

/* 
 *
 * SMARTY UTILS
 *
 * /

/**
 * Creates and returns the contents of a Smarty template
 * @param  string           Path to the template file
 * @param  array            Array of variables to include in the template given by key, value pairs
 * @return SmartyTemplate   Returns the generated Smarty Template
 */
function template($filename, $var) {
    global $smarty;

    if(!isset($smarty))
        throw new Exception("Smarty is not installed");

    $smarty->muteExpectedErrors();
    
    foreach($var as $key => $val)
        $smarty->assign($key, $val);
    
    return $smarty->fetch($filename);
}

/**
 * Creates and returns a template, and adds the contents of a css file to the centralized css engine for compiling
 * @param  string $filename Path of the smarty template file
 * @param  array  $var      Array of variables to include in the template given by key, value pairs
 * @param  string $css_path Path to the file containing CSS data
 * @return void
 */
function template_css($filename, $var, $css_path) {
    global $css;
    $tpl = template($filename, $var);

    // Get CSS info
    $css->add($css_path);

    return $tpl;
}
?>