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
		$pessoa = "";
		$id = $this->session->userdata('id');
		$this->load->model('Usuarios');
		$usuario = $this->Usuarios->get_usuario($id);

		if(!$usuario){
			$this->load->model('Facebooks');
			$usuario = $this->Facebooks->check_login($id);
		}

		if($usuario)
		{
			$this->load->model('Pessoas');
			$dados_user = $this->Pessoas->get_usuario_pessoa($id);
			if($dados_user){
				$pessoa = $dados_user;
			}else{
				$dados_user = $this->Pessoas->get_face_pessoa($id);
				if($dados_user){
					$pessoa = $dados_user;
				}
			}
		}else{
			redirect('conta/sair');
		}

			//Envia as funções existentes para o usuario escolher
			$funcoes = "";
			$this->load->model('Funcoes');
			$funcoes = $this->Funcoes->get_funcoes();

		if($pessoa)
		{	
			//Pagina principal do Usuario ja Cadastrado
			$pessoa_funcao = $this->Pessoas->get_pessoa_funcao($pessoa['pessoa_id']);

			if($pessoa_funcao)
			{
				$dados = array(
				"view" => "pagina/index", 
				"view_menu" => "includes/menu_pagina",
				"usuario_email" => $_SESSION['email']);
			}
		}else{

			//Finalizar cadastro
			$dados = array(
			"funcao" => $funcoes,
			"view" => "usuario/pessoa_cadastro", 
			"view_menu" => "includes/menu_pagina",
			"usuario_email" => $_SESSION['email']);
		}

		$this->load->view('template', $dados);
	}
}