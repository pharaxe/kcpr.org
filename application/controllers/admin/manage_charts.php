<?php

class Manage_charts extends Admin_Controller {
   public function __construct() {
      parent::__construct();

      $this->load->library('ChartsLib');
   }


   public function index($date = false) {

      $simple_chart = new Simple_chart();
      $data = array();

      if ($date) {
         $chartsText = $simple_chart->findByDate($date)->toString();

         $data['date'] = date('Y/m/d', $date);
         $data['charts'] = $chartsText;
      }

      $this->template->add_css('css/admin/datepicker.css');
      $this->template->add_js('js/admin/datepicker.js');
      $this->template->add_js('js/admin/simple_charts.js');
      $this->template->parse_view('content', 'admin/simple_charts.tpl', $data);
      $this->template->render();
   }

   public function submit() {
      if (!isset($_POST['date'])) {
         $this->index();
         return;
      }

      $date = strtotime($_POST['date']);
      $chartsText = $_POST['charts'];

      $simple_chart = new Simple_chart();
      
      // Delete older charts with the same date.
      $oldCharts = $simple_chart->findByDate($date)->get();
      foreach ($oldCharts as $chart) {
         $chart->delete();
      }

      // Add new charts.
      $newCharts = $this->chartslib->parseSimpleCharts($chartsText);
      foreach ($newCharts as $chart) {
         $chart->date = $date;
         $chart->save();
      }

      $this->index($date);
   }

   public function edit($date = null) {
      if ($date == null) {
         $archives = $this->chartslib->getArchives();
         $data = array('dates' => $archives);   

         $this->template->parse_view('content', 'admin/edit_charts.tpl', $data);
         $this->template->render();

      } else {
         // convert date to unixtime
         $date = DateTime::createFromFormat('m-d-Y', $date)->format('U');

         $this->index($date);
      }
   }
}
