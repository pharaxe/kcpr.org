<?php

class Manage_shows extends Admin_Controller {

   public function __construct() {
      parent::__construct();
   }

   public function index() {
      if (isset($_POST['shows'])) {
         $this->saveShows($_POST['shows']);
         $this->template->write('message', 'Shows saved successfully');
      }

      $shows = new Show();
      $shows = $shows->order_by('name', 'asc')->get();

      $data = array(
         'shows' => $shows, 
         'types' => Show::$showTypes
      );

      $this->template->parse_view('content', 'admin/shows.tpl', $data);
      $this->template->add_css('css/tablist.css');
      $this->template->add_css('css/admin/shows.css');
      $this->template->add_js('js/admin/shows.js');
      $this->template->render();
   }

   private function saveShows($data) {
      $length = count($data['id']);

      for ($ndx = 0; $ndx < $length; $ndx++) {
         $show = new Show($data['id'][$ndx]);
      
         if ($data['delete'][$ndx]) {
            $show->delete();
         } else {
            $show->name = $data['name'][$ndx];
            $show->description = $data['description'][$ndx]; 
            $show->genre = $data['genre'][$ndx];
            $show->type = $data['type'][$ndx];

            $show->save();
         }
      }
   }
}
