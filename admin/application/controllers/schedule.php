<?php
class Schedule extends MX_Controller
{
  function __construct()
  {
    parent::__construct();

    //run crontab only
    if (isset($_SERVER['REMOTE_ADDR'])) {
      show_404();
      die;
    }
  }
}
