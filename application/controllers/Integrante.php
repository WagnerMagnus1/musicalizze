<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Integrante extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('logado'))
		{
				redirect('dashboard/index');		
		}										
	}

	public function banda_notifica_usuario()
	{
		if( $this->input->post('notificarbanda') && $this->input->post('notificarbanda') == 'Notificar')
		{
				if($this->input->post('captcha')) redirect ('conta/entrar');

				$this->form_validation->set_rules('banda', 'BANDA', 'required');			
				$this->form_validation->set_rules('funcao', 'FUNÇÃO', 'required');
				$this->form_validation->set_rules('id_pessoa', 'PESSOA', 'required');
	    
				if($this->form_validation->run() == TRUE)
				{
						$id = $this->input->post("id_pessoa");
						$funcao = $this->input->post("funcao");
						$banda = $this->input->post("banda");

						$dados = array(
							"Bandas_banda_id" => $banda,
							"Pessoas_Funcoes_Funcoes_funcao_id" => $funcao,
							"Pessoas_Funcoes_Pessoas_pessoa_id" => $id,
							"integrante_status" => '1'
						);
	
						$this->load->model('Integrantes');
						$salvou = $this->Integrantes->cadastrar($dados);
						if($salvou){
							$this->load->model('Pessoas');
							$pessoa = $this->Pessoas->get_pessoa($id);
							redirect('pessoa/dados?pessoa_id='.$id.'$nome='.$pessoa['pessoa_nome']);
							exit();
						}
				}
		}
		redirect('pagina/erro_salvar');
		exit();	
	}

	public function usuario_notifica_banda()
	{
		if( $this->input->post('notificarbanda') && $this->input->post('notificarbanda') == 'Notificar')
		{
				if($this->input->post('captcha')) redirect ('conta/entrar');

				$this->form_validation->set_rules('banda', 'BANDA', 'required');			
				$this->form_validation->set_rules('funcao', 'FUNÇÃO', 'required');
				$this->form_validation->set_rules('pessoa', 'PESSOA', 'required');
	    
				if($this->form_validation->run() == TRUE)
				{
						$id = $this->input->post("pessoa");
						$funcao = $this->input->post("funcao");
						$banda = $this->input->post("banda");
						$justificativa = $this->input->post("justificativa");

						$dados = array(
							"Bandas_banda_id" => $banda,
							"Pessoas_Funcoes_Funcoes_funcao_id" => $funcao,
							"Pessoas_Funcoes_Pessoas_pessoa_id" => $id,
							"Integrante_justificativa" => $justificativa,
							"integrante_status" => '0'
						);
	
						$this->load->model('Integrantes');
						$salvou = $this->Integrantes->cadastrar($dados);
						if($salvou){
							redirect('banda/dados?banda='.$banda.'&pessoa='.$id);
							exit();
						}
				}
		}
		redirect('pagina/erro_salvar');
		exit();	
	}

	public function notificacao()
	{
		$banda_id = $_GET['banda'];
		if($banda_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Integrantes');$this->load->model('Bandas');
				$banda = $this->Integrantes->get_pessoa_banda_completo_status($banda_id, $pessoa['pessoa_id'],'1');
				$generos = $this->Bandas->get_genero_banda_ativo($banda_id); //Gêneros Ativos
				$administrador = $this->Integrantes->get_administrador_banda($banda_id);

				if($banda && $banda[0]['integrante_status'] == '1'){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "usuario/notificacao_banda", 
					"banda" => $banda,
					"genero" => $generos,
					"adm" => $administrador,
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
				}else{
					redirect('pagina/index');
					exit();
				}
			}else{
				redirect('pagina/index');
				exit();
			}
		}else{
			redirect('pagina/index');
			exit();
		}
		$this->load->view('template', $dados);	
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

	public function consulta_dados_pessoa($pessoa_id)
	{
		$this->load->model('Pessoas');
		$dados_pessoa = $this->Pessoas->get_pessoa($pessoa_id);
		
		return $dados_pessoa;
	}

	public function notificacao_aceitar()
	{
		$dados = json_decode($_POST['dados']);
		$integrante_id = $dados->integrante_id;

		$dados = array(
		"integrante_id" => $integrante_id,
		"integrante_status" => '5',
		"integrante_justificativa" => '',
		"integrante_visualizacao" => '1'
		);

		$this->load->model('Integrantes');
		$aceitou = $this->Integrantes->update($dados);
		return $aceitou;
	}

	public function notificacao_recusar()
	{
		$dados = json_decode($_POST['dados']);
		$integrante_id = $dados->integrante_id;
		$justificativa = $dados->justificativa;
		if($justificativa =="")
		{
			$justificativa = "Sem Justificação.";
		}

		$dados = array(
		"integrante_id" => $integrante_id,
		"integrante_status" => '4',
		"integrante_justificativa" => $justificativa,
		"integrante_visualizacao" => '1'
		);

		$this->load->model('Integrantes');
		$recusou = $this->Integrantes->update($dados);
		return $recusou;
	}

	public function notificacao_aceitar_banda()
	{
		$dados = json_decode($_POST['dados']);
		$integrante_id = $dados->integrante;

		$dados = array(
		"integrante_id" => $integrante_id,
		"integrante_status" => '5',
		"integrante_justificativa" => '',
		"integrante_visualizacao" => '2'
		);

		$this->load->model('Integrantes');
		$aceitou = $this->Integrantes->update($dados);
		return $aceitou;
	}

	public function notificacao_recusar_banda()
	{
		$dados = json_decode($_POST['dados']);
		$integrante_id = $dados->integrante;
		$justificativa = $dados->justificativa;
		if($justificativa =="")
		{
			$justificativa = "Sem Justificação.";
		}

		$dados = array(
		"integrante_id" => $integrante_id,
		"integrante_status" => '3',
		"integrante_justificativa" => $justificativa,
		"integrante_visualizacao" => '2'
		);

		$this->load->model('Integrantes');
		$recusou = $this->Integrantes->update($dados);
		return $recusou;
	}

	public function resposta()
	{
		$banda_id = $_GET['banda'];
		$pessoa_id = $_GET['pessoa'];
		if($banda_id && $pessoa_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Integrantes');
				$banda = $this->Integrantes->get_pessoa_banda_completo_status_visualizacao($banda_id, $pessoa_id, '5', '1');
				if(!$banda){
					$banda = $this->Integrantes->get_pessoa_banda_completo_status_visualizacao($banda_id, $pessoa_id, '4', '1');
				}
				$administrador = $this->Integrantes->get_administrador_banda($banda_id);

				if($banda){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "usuario/notificacao_resposta_banda", 
					"banda" => $banda,
					"adm" => $administrador,
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
				}else{
					redirect('pagina/index');
					exit();
				}
			}else{
				redirect('pagina/index');
				exit();
			}
		}else{
			redirect('pagina/index');
			exit();
		}
		$this->load->view('template', $dados);	
	}

	public function resposta_banda()
	{
		$banda_id = $_GET['banda'];
		$pessoa_id = $_GET['pessoa'];
		if($banda_id && $pessoa_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Integrantes');
				$banda = $this->Integrantes->get_pessoa_banda_completo_status_visualizacao($banda_id, $pessoa_id, '5','2');
				if(!$banda){
					$banda = $this->Integrantes->get_pessoa_banda_completo_status_visualizacao($banda_id, $pessoa_id, '3','2');
				}

				if($banda){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "banda/pedido_resposta_banda", 
					"banda" => $banda,
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
				}else{
					redirect('pagina/index');
					exit();
				}
			}else{
				redirect('pagina/index');
				exit();
			}
		}else{
			redirect('pagina/index');
			exit();
		}
		$this->load->view('template', $dados);	
	}

	public function pedido()//Mostra os músicos que solicitaram participar na banda
	{
		$banda_id = $_GET['banda'];
		$pessoa_id = $_GET['pessoa'];
		if($banda_id && $pessoa_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Integrantes');
				$banda = $this->Integrantes->get_pessoa_banda_completo_status($banda_id, $pessoa_id, '0');//Verifica se realmente esse usuario solicitou participar na banda
				$administrador = $this->Integrantes->get_administrador_banda($banda_id);
				//Pega os dados da pessoa que enviou o pedido para a banda
				$dados_usuario = $this->consulta_dados_pessoa($pessoa_id);
				if($banda){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"dados_usuario" => $dados_usuario,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "banda/pedido_participar_banda", 
					"banda" => $banda,
					"adm" => $administrador,
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
				}else{
					redirect('pagina/index');
					exit();
				}
			}else{
				redirect('pagina/index');
				exit();
			}
		}else{
			redirect('pagina/index');
			exit();
		}
		$this->load->view('template', $dados);	
	}
	
	public function atividade_aviso()//Mostra as novas atividades da banda, em aberto
	{
		$banda_id = $_GET['banda'];
		$pessoa_id = $_GET['pessoa'];
		if($banda_id && $pessoa_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa && $pessoa['pessoa_id'] == $pessoa_id){
				$this->load->model('Integrantes');
				$banda = $this->Integrantes->get_atividade_integrante_aberto_completo($banda_id, $pessoa_id, '5', '2');//Verifica se realmente essa atividade esta em aberto e visivel para o usuario

				if($banda){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "integrante/atividade", 
					"banda" => $banda,
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
				}else{
					redirect('pagina/index');
					exit();
				}
			}else{
				redirect('pagina/index');
				exit();
			}
		}else{
			redirect('pagina/index');
			exit();
		}
		$this->load->view('template', $dados);	
	}

	public function visualizado()
	{
		$dados = json_decode($_POST['dados']);
		$integrante_id = $dados->integrante_id;
		
		$dados = array(
			"integrante_id" => $integrante_id, 
			"integrante_visualizacao" => '0'
		);

		$this->load->model('Integrantes');
		$visualizado = $this->Integrantes->update($dados);
		return $visualizado;
	}
	//Visualizado atividade da banda
	public function visualizado_atividade()
	{
		$dados = json_decode($_POST['dados']);
		$integrante_atividade_id = $dados->integrante_atividade_id;
		
		$dados = array(
			"integrante_atividade_id" => $integrante_atividade_id, 
			"integrante_atividade_visualizacao" => '0'
		);

		$this->load->model('Integrantes');
		$visualizado = $this->Integrantes->update_integrantes_atividades($dados);
		return $visualizado;
	}
	//Cancelar notificação para participar da banda
	public function cancelarconviteatividade()
	{
		$dados = json_decode($_POST['dados']);
		$integrante = $dados->integrante;

		$cancelou = $this->cancelar_convite_banda($integrante);
		return $cancelou;
	}

	public function cancelar_convite_banda($integrante)
	{
		$this->load->model('Integrantes');
		$cancelou = $this->Integrantes->deletar_integrante($integrante);
		return $cancelou;
	}

	public function inativar_integrante()
	{
		$dados = json_decode($_POST['dados']);
		$integrante_id = $dados->integrante;
		$justificativa = $dados->justificativa;
		if($justificativa == "")
		{
			$justificativa = "Sem Justificação.";
		}

		$dados = array(
		"integrante_id" => $integrante_id,
		"integrante_status" => '6',
		"integrante_visualizacao" => '3',
		"integrante_justificativa" => $justificativa
		);

		$this->load->model('Integrantes');
		$recusou = $this->Integrantes->update($dados);
		return $recusou;
	}

	public function integrante_inativo()//Mostra ao usuario quando ele for inativado da banda 
	{
		$integrante = $_GET['integrante'];
		if($integrante){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Integrantes');
				$banda = $this->Integrantes->get_integrante_inativado_banda_id_integrante($integrante);//Verifica se realmente essa atividade esta em aberto e visivel para o usuario

				if($banda){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "integrante/inativo_banda", 
					"banda" => $banda,
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
				}else{
					redirect('pagina/index');
					exit();
				}
			}else{
				redirect('pagina/index');
				exit();
			}
		}else{
			redirect('pagina/index');
			exit();
		}
		$this->load->view('template', $dados);	
	}
}
