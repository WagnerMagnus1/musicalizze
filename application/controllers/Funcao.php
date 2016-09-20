<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Funcao extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('logado'))
		{
				redirect('dashboard/index');		
		}											
	}
	public function cadastrar()
	{
		$dados = json_decode($_POST['dados']);
		$this->load->model('Funcoes');

		$this->Funcoes->cadastrar_funcao($dados);
		$funcoes = $this->Funcoes->get_funcoes();
		echo $funcoes;
	}
}
