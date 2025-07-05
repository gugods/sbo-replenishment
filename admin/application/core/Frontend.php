<?php

class Frontend extends MX_Controller{

    function __construct()
    {
        parent::__construct();
        header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $this->load->helper(array("themes","db_pagination","common","cookie"));
        $this->load->library(array("template","form_validation","pagination"));

        $data = array('theme' => 'default');
        $this->template->set_partial('header','partials/web-header',$data);
        $this->template->set_partial('style','partials/web-style',$data);
        $this->template->set_partial('footer','partials/web-footer',$data);
        $this->template->set_partial('script','partials/web-script',$data);
        $this->template->set_partial('mobile','partials/web-mobile',$data);

        $this->template->title('A23');

    }

} 