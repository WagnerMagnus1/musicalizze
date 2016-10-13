<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    /*public function __construct()
	{
		parent::__construct();

		if($this->session->userdata('logado'))
		{
				redirect('pagina/index');		
		}											
	}*/

	public function cadastro()
	{
		$dados = array ('view' => "usuario/cadastrar", 'view_menu' => 'includes/menu');
		$this->load->view('template', $dados);
	}

	public function cadastrar()
	{

		if( $this->input->post('cadastrar') && $this->input->post('cadastrar') == 'cadastrar')
		{

			if($this->input->post('captcha')) redirect ('conta/entrar');

			$this->form_validation->set_rules('email', 'EMAIL', 'required|valid_email|is_unique[users.user_email]');	
			$this->form_validation->set_rules('senha', 'SENHA', 'required');			
			$this->form_validation->set_rules('confirmasenha', 'CONFIRMAR SENHA', 'required|matches[senha]');
			
			if($this->form_validation->run() == TRUE)
			{
				$dados_usuario = array(
					"user_email" => $this->input->post("email"),
					"user_password" => $this->input->post("senha")
				);
				$this->load->model('Usuarios');
				$cadastrou = $this->Usuarios->cadastrar_usuario($dados_usuario);

				if($cadastrou)
				{

					$usuario = $this->Usuarios->get_usuario_email($dados_usuario['user_email']);

					$session = array(
							'id'        => $usuario['user_id'],
					        'email'     => $usuario['user_email'],
					        'created'   => $usuario['user_data'],
					        'logado' => TRUE
					);
					$this->session->set_userdata($session);

					redirect('pagina/index');
					exit();
				}else{
					$alerta = array(
						'class' => 'danger',
						'mensagem' => 'Atenção! Usuario não cadastrado...' 
					);
				}

			}else{
				$alerta = array(
				'class' => 'danger',
				'mensagem' => 'Atenção! Erro na validação do formulário! Verifique! <br>'.validation_errors() 
				);
			}
		}

		$dados = array
		(
			'alerta' => $alerta,
			"perfil" => 'http://pingendo.github.io/pingendo-bootstrap/assets/user_placeholder.png',
			'view' => 'usuario/cadastrar',
			'view_menu' => 'includes/menu'
		);

		$this->load->view('template', $dados);		
	}

	public function email_disponivel()
	{
		$dados = json_decode($_POST['dados']);
		$this->load->model('Usuarios');

		$emailexistente = $this->Usuarios->get_usuario_email($dados->email);
		if($emailexistente)
		{
			echo $emailexistente['user_email'];
		}else{
			echo "Esse email é valido.";
		}
	}
}
