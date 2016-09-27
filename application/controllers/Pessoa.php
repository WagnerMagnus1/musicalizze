<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Pessoa extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('logado'))
		{
				redirect('dashboard/index');		
		}										
	}

	public function crop()
	{
		$dados = json_decode($_POST['dados']);

		if($dados){
			$ini_filename = $dados->img; // path da imagem
			$im = imagecreatefromjpeg($ini_filename); // criando instancia jpeg

			//definindo coordenadas de corte
			$to_crop_array = array('x' =>$dados->x , 'y' => $dados->y, 'width' => $dados->w, 'height'=> $dados->h); 
			$thumb_im = imagecrop($im, $to_crop_array); // recortando imagem
			$email = $_SESSION['email'];
			$diretorio = getcwd();
			imagejpeg($thumb_im, $diretorio.'/public/imagens/perfil/'.$email.'.png', 100); // salvando nova instancia
			//$novoDestino = base_url('imagens/perfil/'.$email.'.png');
			//move_uploaded_file ( $thumb_im , $destino );
			return true;
		}else{
			return false;
		}
	}

	public function cadastrar()
	{

		if( $this->input->post('cadastrar') && $this->input->post('cadastrar') == 'cadastrar')
		{

				if($this->input->post('captcha')) redirect ('conta/entrar');

				$this->form_validation->set_rules('id_pessoa', 'ID DO USUARIO', 'required');	
				$this->form_validation->set_rules('nome', 'NOME', 'required');			
				$this->form_validation->set_rules('nascimento', 'DATA DE NASCIMENTO', 'required');
				$this->form_validation->set_rules('sexo', 'SEXO', 'required');
				$this->form_validation->set_rules('estado', 'ESTADO', 'required');
				$this->form_validation->set_rules('cidade', 'CIDADE', 'required');
	    
				if($this->form_validation->run() == TRUE)
				{

					$id = $this->input->post("id_pessoa");
					$existe = $this->verificaid($id); 				//VERIFICA SE O USUARIO ESTA LOGADO COM O FACE 
					if($existe)
					{

						$dados_pessoa = array(
						"Users_Facebook_facebook_id" => $this->input->post("id_pessoa"),
						"pessoa_nome" => $this->input->post("nome"),
						"pessoa_sobrenome" => $this->input->post("sobrenome"),
						"pessoa_nascimento" => $this->input->post("nascimento"),
						"pessoa_telefone" => $this->input->post("txttelefone"),
						"pessoa_endereco" => $this->input->post("endereco"),
						"pessoa_cidade" => $this->input->post("cidade"),
						"pessoa_estado" => $this->input->post("estado"),
						"pessoa_foto" => $this->input->post("perfil"),
						"pessoa_obs" => $this->input->post("observacao"),
						"pessoa_contato" => $this->input->post("contato"),
						"pessoa_latitude" => $this->input->post("latitude"),
						"pessoa_longitude" => $this->input->post("longitude"),
						"pessoa_sexo" => $this->input->post("sexo")
						);
					}else{
						$dados_pessoa = array(
						"Users_user_id" => $this->input->post("id_pessoa"),
						"pessoa_nome" => $this->input->post("nome"),
						"pessoa_sobrenome" => $this->input->post("sobrenome"),
						"pessoa_nascimento" => $this->input->post("nascimento"),
						"pessoa_telefone" => $this->input->post("txttelefone"),
						"pessoa_endereco" => $this->input->post("endereco"),
						"pessoa_cidade" => $this->input->post("cidade"),
						"pessoa_estado" => $this->input->post("estado"),
						"pessoa_foto" => $this->input->post("perfil"),
						"pessoa_obs" => $this->input->post("observacao"),
						"pessoa_contato" => $this->input->post("contato"),
						"pessoa_latitude" => $this->input->post("latitude"),
						"pessoa_longitude" => $this->input->post("longitude"),
						"pessoa_sexo" => $this->input->post("sexo")
					 );	
					}
					$this->load->model('Pessoas');	
					$cadastrou = $this->Pessoas->cadastrar_pessoa($dados_pessoa);

					if($cadastrou)
					{
						$existe = $this->verificaid($id); 
						if($existe){$pessoa = $this->Pessoas->get_face_pessoa($id);}else{$pessoa = $this->Pessoas->get_usuario_pessoa($id);}

						$funcao = $this->input->post("funcao");

							for ($i=0;$i<count($funcao);$i++)
							{
								$dados_funcao = array(
								"Pessoas_pessoa_id" => $pessoa['pessoa_id'],
								"Funcoes_funcao_id" => $funcao[$i],
								"disponibilidade" => '1'
								);

								$cadastra_funcao = $this->Pessoas->vincular_funcao($dados_funcao);
								
								if(!$cadastra_funcao){	
								$this->Pessoas->delete_pessoa($pessoa['pessoa_id']);
								}
							}
						redirect('pagina/index');
						exit();	
					}else{
						$alerta = array(
							'class' => 'danger',
							'mensagem' => 'Atenção! Cadastro não finalizado...' 
						);
					}
				}else{
					$alerta = array(
					'class' => 'danger',
					'mensagem' => 'Atenção! Erro na validação do formulário! Verifique! <br>'.validation_errors() 
					);
				}
		}

	$this->load->model('Funcoes');
	$funcoes = $this->Funcoes->get_funcoes();

	$dados = array(
	"alerta" => "O cadastro não foi salvo com sucesso! Por favor, tente novamente.",
	"funcao" => $funcoes,
	"view" => "usuario/pessoa_cadastro", 
	"view_menu" => "includes/menu_pagina",
	"usuario_email" => $_SESSION['email']);
		


	$this->load->view('template', $dados);
	}

	public function verificaid($id)
	{	
		$this->load->model('Facebooks');
		$usuario = $this->Facebooks->get_usuario_email($id);
		return $usuario;
	}

	public function meusdados()
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
			$pessoa_funcao = "";
			$pessoa_funcao = $this->Pessoas->get_pessoas_funcoes_ativo($pessoa['pessoa_id']);
			$idade = $this->calcula_idade($pessoa['pessoa_nascimento']);
			//Envia para a pagina de dados do usuario
			$dados = array(
			"funcao" => $pessoa_funcao,
			"dados" => $pessoa,
			"pessoa_idade" => $idade,
			"view" => "usuario/meus_dados", 
			"view_menu" => "includes/menu_pagina");
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

	public function editar()
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
			$string_funcao = "";
			$string_funcao_inativo = "";
			$pessoa_funcao = $this->Pessoas->get_pessoas_funcoes_ativo($pessoa['pessoa_id']);
			if($pessoa_funcao){
				foreach($pessoa_funcao as $funcao){
					$string_funcao = $funcao['funcao_id'].','.$string_funcao;
				}
			}else{
				$string_funcao = "";
			}
			$pessoa_funcao = $this->Pessoas->get_pessoas_funcoes_inativo($pessoa['pessoa_id']);
			if($pessoa_funcao){
				foreach($pessoa_funcao as $funcao){
					$string_funcao_inativo = $funcao['funcao_id'].','.$string_funcao_inativo;
				}
			}else{
				$string_funcao_inativo = "";
			}
			//Envia para a pagina de dados do usuario
			$dados = array(
			"funcao_ativa" => $string_funcao,
			"funcao_inativa" => $string_funcao_inativo,
			"funcao" => $funcoes,
			"dados" => $pessoa,
			"view" => "usuario/meus_dados_editar", 
			"view_menu" => "includes/menu_pagina");
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

	public function salvar_editar()
	{
		
		$alerta = "";
		if( $this->input->post('cadastrar') && $this->input->post('cadastrar') == 'cadastrar')
		{

				if($this->input->post('captcha')) redirect ('conta/entrar');

				$this->form_validation->set_rules('id_usuario', 'ID DO USUARIO', 'required');
				$this->form_validation->set_rules('pessoa_id', 'ID DA PESSOA', 'required');	
				$this->form_validation->set_rules('nome', 'NOME', 'required');			
				$this->form_validation->set_rules('nascimento', 'DATA DE NASCIMENTO', 'required');
				$this->form_validation->set_rules('sexo', 'SEXO', 'required');
				$this->form_validation->set_rules('estado', 'ESTADO', 'required');
				$this->form_validation->set_rules('cidade', 'CIDADE', 'required');
	    
				if($this->form_validation->run() == TRUE)
				{

					$id = $this->input->post("id_usuario");
					$existe = $this->verificaid($id); 				//VERIFICA SE O USUARIO ESTA LOGADO COM O FACE 
					if($existe)
					{
						$dados_pessoa = array(
						"pessoa_id" => $this->input->post("pessoa_id"),
						"Users_Facebook_facebook_id" => $this->input->post("id_usuario"),
						"pessoa_nome" => $this->input->post("nome"),
						"pessoa_sobrenome" => $this->input->post("sobrenome"),
						"pessoa_nascimento" => $this->input->post("nascimento"),
						"pessoa_telefone" => $this->input->post("txttelefone"),
						"pessoa_endereco" => $this->input->post("endereco"),
						"pessoa_cidade" => $this->input->post("cidade"),
						"pessoa_estado" => $this->input->post("estado"),
						"pessoa_foto" => $this->input->post("perfil"),
						"pessoa_obs" => $this->input->post("observacao"),
						"pessoa_contato" => $this->input->post("contato"),
						"pessoa_latitude" => $this->input->post("latitude"),
						"pessoa_longitude" => $this->input->post("longitude"),
						"pessoa_sexo" => $this->input->post("sexo")
						);
					}else{
						$dados_pessoa = array(
						"pessoa_id" => $this->input->post("pessoa_id"),
						"Users_user_id" => $this->input->post("id_usuario"),
						"pessoa_nome" => $this->input->post("nome"),
						"pessoa_sobrenome" => $this->input->post("sobrenome"),
						"pessoa_nascimento" => $this->input->post("nascimento"),
						"pessoa_telefone" => $this->input->post("txttelefone"),
						"pessoa_endereco" => $this->input->post("endereco"),
						"pessoa_cidade" => $this->input->post("cidade"),
						"pessoa_estado" => $this->input->post("estado"),
						"pessoa_foto" => $this->input->post("perfil"),
						"pessoa_obs" => $this->input->post("observacao"),
						"pessoa_contato" => $this->input->post("contato"),
						"pessoa_latitude" => $this->input->post("latitude"),
						"pessoa_longitude" => $this->input->post("longitude"),
						"pessoa_sexo" => $this->input->post("sexo")
					 );	
					}
//ALTERAR DADOS DA PESSOA
					$this->load->model('Pessoas');	
					$alterou = $this->Pessoas->update($dados_pessoa);
					
//FAZ A ALTERAÇÃO DAS FUNÇÕES QUE O USUARIO SELECIONOU
					if($existe){$pessoa = $this->Pessoas->get_face_pessoa($id);}else{$pessoa = $this->Pessoas->get_usuario_pessoa($id);}
						//Aqui estão todas as funções que o usuario deixou marcado
						$funcao_form = $this->input->post("funcao");
						//Todas as funcoes ja vinculadas pelo usuario no banco de dados
						$funcao_ativo = $this->Pessoas->get_pessoas_funcoes_ativo($pessoa['pessoa_id']);
						$funcao_inativo = $this->Pessoas->get_pessoas_funcoes_inativo($pessoa['pessoa_id']);

							
						if($funcao_ativo){
							//desativar funcão
							foreach($funcao_ativo as $funcao)
							{
								$remover = 0;
								for ($i=0;$i<count($funcao_form);$i++)
								{
									if($funcao['Funcoes_funcao_id'] == $funcao_form[$i])
									{
										//Não faz nada
									}else{
										$remover = $remover + 1;
									}
								}
								if($remover == $i)
								{
									$dados_funcao = array(
									"Pessoas_pessoa_id" => $funcao['Pessoas_pessoa_id'],
									"Funcoes_funcao_id" => $funcao['Funcoes_funcao_id'],
									"disponibilidade" => '0'
									);
										//verifica se existe atividade em aberto para essa função que o usuario quer inativar
										$this->load->model('Atividades');
										$atividade_aberto = $this->Atividades->get_atividade_aberto_funcao_pessoa($funcao['Pessoas_pessoa_id'], $funcao['Funcoes_funcao_id']);
										if($atividade_aberto){
											$alerta = array(
											'class' => 'danger',
											'mensagem' => 'Atenção! A função '.$atividade_aberto[0]['funcao_nome'].' possui uma atividade em aberto, por favor finalize a atividade para poder desativar essa função.<br>'.validation_errors() 
											);
											
										}else{
											//desativar função do usuario
											$desativar = $this->Pessoas->update_disponibilidade_funcao($dados_funcao);
										}
								}
							}
						}
						if($funcao_inativo){
							//ativar funcao 
							foreach($funcao_inativo as $funcao)
							{
								for ($i=0;$i<count($funcao_form);$i++)
								{
									if($funcao['Funcoes_funcao_id'] == $funcao_form[$i])
									{
										$dados_funcao = array(
										"Pessoas_pessoa_id" => $funcao['Pessoas_pessoa_id'],
										"Funcoes_funcao_id" => $funcao['Funcoes_funcao_id'],
										"disponibilidade" => '1'
										);
										//ativa função do usuario
										$ativar = $this->Pessoas->update_disponibilidade_funcao($dados_funcao);
									}
								}
							}
						}
						$funcao_base = $this->Pessoas->get_pessoas_funcoes($pessoa['pessoa_id']); 
							//inserir nova função
							for ($i=0;$i<count($funcao_form);$i++)
							{
								$add = 0;
								for ($f=0;$f<count($funcao_base);$f++)
								{
									if($funcao_form[$i] == $funcao_base[$f]['Funcoes_funcao_id'])
									{
										
									}else{
										$add = $add + 1;
									}
								}

								if($add == $f)
								{
									$dados_funcao = array(
									"Pessoas_pessoa_id" => $pessoa['pessoa_id'],
									"Funcoes_funcao_id" => $funcao_form[$i],
									"disponibilidade" => '1'
									);

									$cadastra_funcao = $this->Pessoas->vincular_funcao($dados_funcao);
								}
							}

				//redirect('pessoa/meusdados');
				//exit();	
				}else{
					$alerta = array(
					'class' => 'danger',
					'mensagem' => 'Atenção! Erro na validação do formulário! Verifique! <br>'.validation_errors() 
					);
				}
		}

		$pessoa_funcao = "";
		$pessoa_funcao = $this->Pessoas->get_pessoas_funcoes_ativo($pessoa['pessoa_id']);
		$idade = $this->calcula_idade($pessoa['pessoa_nascimento']);

	$this->load->model('Funcoes');
	$funcoes = $this->Funcoes->get_funcoes();
	$dados = array(
	"alerta" => $alerta,
	"funcao" => $pessoa_funcao,
	"dados" => $pessoa,
	"pessoa_idade" => $idade,
	"view" => "usuario/meus_dados_erro", 
	"view_menu" => "includes/menu_pagina",
	"usuario_email" => $_SESSION['email']);
		
	$this->load->view('template', $dados);
	}
	public function calcula_idade($data)
	{
		// Separa em dia, mês e ano
	    list($ano, $mes, $dia) = explode('-', $data);
	   
	    // Descobre que dia é hoje e retorna a unix timestamp
	    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
	    // Descobre a unix timestamp da data de nascimento do fulano
	    $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);
	   
	    // Depois apenas fazemos o cálculo já citado :)
	    $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
	    return $idade;
	}

	public function dados()
	{
		echo "dados da pessoa";
		exit();
	}

	public function salvar_foto()
	{
		$dados = json_decode($_POST['dado']);
		$dados_foto = array(
			"pessoa_id" => $dados->pessoa_id,
			"pessoa_foto" => $dados->pessoa_foto
		);
		$this->load->model('Pessoas');
		$editou = $this->Pessoas->update($dados_foto);

		$ini_filename = $dados->img; // path da imagem
		$imagem = imagecreatefromjpeg($ini_filename); // criando instancia jpeg
		//$novoDestino = base_url('public/imagens/perfil/'.$dados->pessoa_nome.$dados_foto['pessoa_id'].'.jng');
		//move_uploaded_file( $imagem , $novoDestino );
		$diretorio = getcwd();
		chmod(base_url('public/imagens/perfil'), 0777);
		imagejpeg($imagem, $diretorio.'/public/imagens/perfil/'.$dados_foto['pessoa_id'].$dados->pessoa_nome.'.jpg', 100); // salvando nova instancia
	}
}