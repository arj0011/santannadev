<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Third Party Helper
 * Author: Sorav Garg
 * Author Email: soravgarg123@gmail.com
 * version: 1.0
 */
/**
 * [To get bitly short link]
 * @param string $link
 */
if (!function_exists('get_bitly_short_link')) {

    function get_bitly_short_link($link) {
        $ci = &get_instance();
        $params = array('bitlyLogin' => 'sureshp', 'bitlyAPIKey' => 'R_9cbcbd82679cac126258e3919eb41849');
        $ci->load->library('ext_conv_link', $params);
        $link = $ci->ext_conv_link->ExtractAndConvert($link);
        return $link;
    }

}

/* End of file thirdparty_helper.php */
/* Location: ./system/application/helpers/thirdparty_helper.php */
?>