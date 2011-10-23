<?php

class Manage_schedule extends Admin_Controller {
   public static $days = array(
      'Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'
   );

   public function __construct() {
      parent::__construct();
      $this->template->set_template('admin');
      $this->load->library('ShowsLib');
   }

   public function index() {
      if (isset($_POST['schedule'])) {
         $this->saveSchedule($_POST['schedule']);
         $this->template->write('message', 'Schedule saved successfully');
      }

      $shows = new Show();
      $shows->order_by('name', 'asc')->get();

      $schedule = $this->showslib->getScheduleArray();

      $data = array(
         'schedule' => $schedule,
         'days' => self::$days,
         'shows' => $shows
      );

      $this->template->parse_view('content', 'admin/schedule.tpl', $data);
      $this->template->add_js('js/admin/schedule.js');
      $this->template->add_js('js/admin/datepicker.js');
      $this->template->add_css('css/admin/schedule.css');
      $this->template->add_css('css/admin/datepicker.css');
      $this->template->render();
   }

   private function saveSchedule($data) {
      foreach ($data as $time => $days) {
         foreach ($days as $day => $spot) {
            $schedule = new Spot($spot['id']);

            if ($spot['delete'] == 'true') {
               $schedule->delete();
            } else {
               $schedule->show_id = $spot['showid'];
               $schedule->day = $day;
               $schedule->setClockTime($time);

               $schedule->save();
            }
         }
      }
   }
}
