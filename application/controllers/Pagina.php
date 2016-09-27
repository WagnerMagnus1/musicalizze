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
			$funcaoativa = $this->Pessoas->get_pessoas_funcoes_ativo($pessoa['pessoa_id']);
			$atividades_aberto = $this->retornar_atividades_aberto($pessoa['pessoa_id']);
			
			//verifica se essa atividade tem a participação de uma banda
			$this->load->model('Atividades');
			$atividades_banda = "";
			if($atividades_aberto){
				foreach ($atividades_aberto as $atividade) {
					$atividades_banda = $this->Atividades->retornar_atividade_banda($atividade['atividade_id']);
				}
			}
			if($atividades_banda){
				//AQUI IRA PARA OUTRO CAMINHO, MOSTRANDO NA PAGINA/INDEX AS BANDAS ADMINISTRADORAS DAS ATIVIDADES
			}else{
				if($pessoa_funcao)
				{		
					//Envia todos os participantes de cada atividade
					for ($i=0;$i<count($atividades_aberto);$i++) {
						$lista_integrantes[$i] = array("integrantes" => $this->Atividades->retornar_pessoas_atividade($atividades_aberto[$i]['atividade_id']));	
					}
					//Encaminha todos os dados levantados para a View Pagina/Index
					$dados = array(
					"pessoa" => $pessoa,
					"funcaoativa" => $funcaoativa,
					"atividades_aberto" => $atividades_aberto,
					"lista_integrantes" => $lista_integrantes,
					"view" => "pagina/index", 
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
				}
			}
		}else{
				if($_SESSION['email'] == "admin@admin.com"){
					//Pagina do administrador
					$dados = array(
					"view" => "pagina/administrador", 
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
				}else{
				//Finalizar cadastro
				$dados = array(
				"funcao" => $funcoes,
				"view" => "usuario/pessoa_cadastro", 
				"view_menu" => "includes/menu_pagina",
				"usuario_email" => $_SESSION['email']);
				}
		    }

		$this->load->view('template', $dados);
	}

	public function erro_salvar()
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
			$funcaoativa = $this->Pessoas->get_pessoas_funcoes_ativo($pessoa['pessoa_id']);

			$alerta = array(
			'class' => 'danger',
			'mensagem' => 'Atenção! Ocorreu um erro inesperado, por favor tente novamente ou contate o administrador. <br>'.validation_errors() 
			);
			if($pessoa_funcao)
			{
				$dados = array(
				"pessoa" => $pessoa,
				"funcaoativa" => $funcaoativa,
				"alerta" => $alerta,
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

	public function retornar_atividades_aberto($pessoa_id)
	{
		    $this->load->model('Atividades');
		    //Recebo todas as atividades em que a pessoa esta marcada
		    $atividades = "";
		    $atividades = $this->Atividades->get_pessoa_atividade_em_aberto($pessoa_id);
		    if($atividades){
		    	foreach ($atividades as $a) {
		    		$venceu = $this->data_validade($a['atividade_data']);
		    		if($venceu){
		    			$dados_atividade = array(
		    				"atividade_id" => $a['atividade_id'],
		    				"atividade_status" => "2"
		    			);
		    			$altera_atividade = $this->Atividades->update($dados_atividade);
		    		}
		    	}
		    }

		$atividades = $this->Atividades->get_pessoa_atividade_em_aberto($pessoa_id);
		return $atividades;
	}

	public function data_validade($data)
	{
		date_default_timezone_set('America/Sao_Paulo');
		$dt_atual		= date("Y-m-d"); // data atual
		$timestamp_dt_atual   = strtotime($dt_atual); // converte para timestamp Unix

		$dt_expira		= substr($data, 0, 10);  // data de expiração do anúncio
		$timestamp_dt_expira	= strtotime($dt_expira); // converte para timestamp Unix
		// data atual é maior que a data de expiração

		if ($timestamp_dt_atual > $timestamp_dt_expira) // true
		  return true;
		else // false
		  return false;
	}	
}