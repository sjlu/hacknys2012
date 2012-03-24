<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Frontpage extends CI_Controller {

   /*
    * index()
    * This loads the default index view.
    *
    * @author sjlu
    */
   public function index()
   {
      $this->load->view('include/header');
      $this->load->view('frontpage');
      $this->load->view('include/footer');
   }

}
