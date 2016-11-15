<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Administrador extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('logado'))
		{
				redirect('dashboard/index');		
		}											
	}
	public function cadastrarFuncao()
	{
		$dados = json_decode($_POST['dados']);
		$this->load->model('Funcoes');

		$this->Funcoes->cadastrar_funcao($dados);
		$funcoes = $this->Funcoes->get_funcoes();
		echo $funcoes;
	}

	public function cadastrarGenero()
	{
		$dados = json_decode($_POST['dados']);
		$this->load->model('Generos');

		$this->Generos->cadastrar_genero($dados);
		$generos = $this->Generos->get_generos();
		echo $generos;
	}

	public function formulario()
	{
		//Verifica se a pessoa logada possui dados
		$pessoa_logado = $this->dados_pessoa_logada();
		$dados = array (
			'dados' => $pessoa_logado,
			'pessoa' => $pessoa_logado,
			'perfil' => @$pessoa_logado['pessoa_foto'],
			'view' => "pagina/contato_administrador", 
			'view_menu' => 'includes/menu_pagina'
		);
		$this->load->view('template', $dados);
	}

	public function enviar_email()
	{
		$input = $this->input->post('captcha');
		if($this->input->post('captcha')) redirect ('conta/entrar');

		$arquivo = $this->input->post('email'); //Comentário do email enviado
		$nome = $this->session->userdata('email');
		$data_envio = date('d/m/Y');
		$hora_envio = date('H:i:s');

		 // emails para quem será enviado o formulário
		  $emailenviar = "wagnerserafa@gmail.com";
		  $destino = $emailenviar;
		  $assunto = "Contato pelo site Musicalizze";

		  // É necessário indicar que o formato do e-mail é html
		  $headers  = 'MIME-Version: 1.0' . "\r\n";
		      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		      $headers .= 'From:'.$nome;
		  //$headers .= "Bcc: $EmailPadrao\r\n";
		  
		  $enviaremail = mail($destino, $assunto, $arquivo, $headers);
		  if($enviaremail){
		   redirect('administrador/formulario');exit(); 
		  } else {
		  $mgm = "ERRO AO ENVIAR E-MAIL!";
		  echo $mgm;
		  }
	}

	public function dados_pessoa_logada()
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
			exit();
		}
		return $pessoa;
	}
}
