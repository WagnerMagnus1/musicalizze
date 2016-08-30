<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Pagina extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('logado'))
		{
				redirect('dashboard/index');		
		}											
	}

	public function index()
	{
		$id = $this->session->userdata('id');
		$this->load->model('Usuarios');
		$usuario = $this->Usuarios->get_usuario($id);

		$this->load->model('Pessoas');
		$pessoa = $this->Pessoas->get_pessoa($id);

		if($pessoa)
		{	
			//Pagina principal do Usuario ja Cadastrado
			$dados = array(
			"view" => "pagina/index", 
			"view_menu" => "includes/menu_pagina",
			"usuario_email" => $usuario['user_email']);

		}else{

			//Finalizar cadastro
			$dados = array(
			"view" => "usuario/pessoa_cadastro", 
			"view_menu" => "includes/menu_pagina",
			"usuario_email" => $usuario['user_email']);
		}


		$this->load->view('template', $dados);
	}
}