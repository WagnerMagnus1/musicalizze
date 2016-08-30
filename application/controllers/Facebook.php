<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facebook extends CI_Controller {


	public function index()
	{

		$usuario = json_decode($_POST['dados']);
		
        if($usuario->id)
        {
        	if($usuario->nome)
        	{
        		if($usuario->email)
        		{
        			$this->verifica_cadastro($usuario);
        		}else{
		        	$alerta = array(
							"class" => "danger", 
							"mensagem" => "Problemas de autenticação, por favor realize o login novamente."
							);
        		}
        	}else{
	        	$alerta = array(
						"class" => "danger", 
						"mensagem" => "Problemas de autenticação, por favor realize o login novamente."
						);
        	}
        }else{
        	$alerta = array(
						"class" => "danger", 
						"mensagem" => "O sistema não encontrou seus dados do facebook, por favor verifique e tente novamente."
						);
        }

	}

	public function verifica_cadastro($usuario)
	{
		$this->load->model('Facebooks');
		$login_existe = $this->Facebooks->check_login($usuario->id);
		
		if($login_existe){
			$this->iniciarSessao($login_existe);
		}else{
			$this->cadastrar_usuario($usuario);
		}
	}

	public function cadastrar_usuario($usuario)
	{
		$dados_usuario = array(
			"facebook_id" => $usuario->id,
			"facebook_email" => $usuario->email
			);

			$this->load->model('Facebooks');

			$cadastrou = $this->Facebooks->cadastrar_usuario($dados_usuario);

		if($cadastrou)
			{
				$userface = $this->Facebooks->get_usuario_email($dados_usuario['facebook_id']);
				$this->iniciarSessao($userface);
			}else{
				$alerta = array(
					'class' => 'danger',
					'mensagem' => 'Atenção! Usuario não cadastrado...' 
				);
			}

	}

	public function iniciarSessao($dados)
	{
		
		$session = array(
				'id'        => $dados['facebook_id'],
		        'email'     => $dados['facebook_email'],
		        'created'   => $dados['facebook_data'],
		        'logado' => TRUE
		);

		//Inicializa a sessão
		$this->session->set_userdata($session);
	}
}
