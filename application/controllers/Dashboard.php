<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	//Direciona o usuario logado ou não logado a sua pagina no site
	public function index()
	{
		$logado = $this->session->userdata('logado');

		if($logado == true)
		{
			redirect('pagina/index');

		}else{
			$dados = array('view' => "dashboard/index", 'view_menu' => 'includes/menu');
		}
		
		$this->load->view('template', $dados);
		//$this->load->view('facebook');
	}
}
