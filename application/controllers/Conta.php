<?php /**
* 
*/
class Conta extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if($this->session->userdata('logado') == TRUE)
		{
			if(!$this->uri->segment(2) == "sair")
			{
				redirect('Pagina/index');
			}
		}											
	}

	public function entrar()
	{
   		$alerta	= null;

		if($this->input->post('entrar') == "entrar")
		{

			if($this->input->post('captcha')) redirect('dashboard/index');

			$this->form_validation->set_rules('email','EMAIL', 'required|valid_email');
			$this->form_validation->set_rules('senha','SENHA', 'required');

			if($this->form_validation->run() == TRUE)
			{	

				$this->load->model('usuarios');

				$email = $this->input->post('email');
				$senha = $this->input->post('senha');

				$login_existe = $this->usuarios->check_login($email, $senha);

				if($login_existe){
					//Login Autorizado--- iniciar sessão
					$usuario = $login_existe;

					//Configura os dados da sessão
					$this->iniciar_sessao($usuario);
					redirect('pagina/index');	
				}else{
					$alerta = array(
						"class" => "danger", 
						"mensagem" => "Atenção! Login inválido, senha ou email incorretos."
						);
				}
			}else{
				$alerta = array("class" => "danger", "mensagem" => "Atenção! Falha na validação do formulário!<br>". validation_errors());
			}
		}

		$dados = array(
			"alerta" => $alerta, 
			"view" => 'dashboard/index', 
			"view_menu" => "includes/menu",
			"hidemenu" => true);

		$this->load->view('template', $dados);
	}

	public function iniciar_sessao($usuario)
	{
		$session = array(
				'id'        => $usuario['user_id'],
		        'email'     => $usuario['user_email'],
		        'created'   => $usuario['user_data'],
		        'logado' => TRUE
		);

		//Inicializa a sessão
		$this->session->set_userdata($session);
	}

    public function verifica_email()
	{
		$dados = json_decode($_POST['dados']);
		$this->load->model('Usuarios');

		$emailexistente = $this->Usuarios->get_usuario_email($dados->email);
		if($emailexistente)
		{
			echo $emailexistente['user_email'];
		}else{
			echo "Email não cadastrado";
		}
	}
	public function verifica_senha()
	{
		$dados = json_decode($_POST['dados']);
		$this->load->model('Usuarios');

		$emailexistente = $this->Usuarios->get_usuario_email($dados->email);
		if($emailexistente)
		{
			$senhaexiste = $this->Usuarios->check_login($dados->email, $dados->senha);
			if($senhaexiste)
			{
				echo $senhaexiste['user_password'];
			}else{
				
			}

		}else{
			echo "Email não cadastrado";
		}
	}

	public function sair()
	{
		$this->session->sess_destroy();

		redirect('dashboard/index');
	}

}