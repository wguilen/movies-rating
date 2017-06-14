<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('loadBaseView'))
{
    function loadBaseView($content = '', $title = null, $menuItem = null)
    {
        if (is_null($CI = &get_instance()))
        {
            /** @var $error CI_Exceptions */
            $error = &load_class('Exceptions', 'core');
            exit($error->show_error('loadBaseView Helper' ,'loadBaseView() could not obtain $CI instance.'));
        }

        $baseViewData = array(
            'content'   => $content,
            'menuItem'  => !is_null($menuItem) ? $menuItem : 'usuarios');

        if (!is_null($title))
        {
            $baseViewData['title'] = $title;
        }

        return $CI->load->view('base', $baseViewData);
    }
}