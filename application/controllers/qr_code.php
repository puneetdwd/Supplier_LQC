<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class QR_code extends Admin_Controller {
        
    public function __construct() {
        parent::__construct(true);

        //render template
        $this->template->write('title', 'LQC | QR Code Module');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');
		
		$page_new = 'qr_code';
		
		//For page hits
		$this->hits($page_new);

    }
    
    public function index() {
         $data = array();
	//set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';
     $qrlib = 'assets/phpqrcode/qrlib.php';
    include($qrlib);
// echo 'qwe';    exit;
	if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    }
}