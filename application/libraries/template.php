<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Template {

    function show($view, $data = array())
    {
        // Get current CI Instance
        $CI = & get_instance();

        // Load template views
        $CI->load->view('templates/header_view', $data);
        $CI->load->view($view, $data);
        $CI->load->view('templates/footer_view', $data);
    }
 
    function menu($view)
    {
        // Get current CI Instance
        $CI = & get_instance();

        // Load menu template
        $CI->load->view('templates/navbar_view', array('view' => $view));
    }
 
}
 
/* End of file Template.php */