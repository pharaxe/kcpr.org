<?php

class Admin_Controller extends CI_Controller {
   public function __construct() {
      parent::__construct();

      $this->load->library('session');
      $this->load->library('Simplelogin');

      $this->template->set_template('admin');

      $this->login();
   }

   public function login() {
      $data = array();

      if (isset($_POST['user']) && isset($_POST['pass'])) {
         // User wants to log in.
         if (!$this->simplelogin->login($_POST['user'], $_POST['pass'])) {
            // Incorrect log in.
            $data['message'] = "Incorrect username or password";
         }
      }
         
      if (!$this->session->userdata('logged_in')) {
         $this->template->set_template('admin');
         $this->template->parse_view('content', 'admin/login.tpl', $data);
         $this->template->render();
         $this->output->_display();  // hack to display login screen before exiting.
         exit();
      } 
   }
}
