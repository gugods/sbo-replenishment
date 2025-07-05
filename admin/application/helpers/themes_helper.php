<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

if (!function_exists('theme_url')) {

    function theme_url($theme='default')
    {
        return base_url().'themes/'.$theme;
    }

}

if (!function_exists('theme_assets_url')) {

	function theme_assets_url($theme='default')
	{
	    return base_url().'themes/'.$theme.'/assets/';
	}

}

if (!function_exists('theme_menu')) {

	function theme_menu($page='',$class = '')
	{
		$CI =& get_instance();
		return (strtolower($CI->uri->segment(1))==strtolower($page)) ? 'active'.$class : '';
	}
}