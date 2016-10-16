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
	"perfil" => $pessoa['pessoa_foto'],
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
			"pessoa" => $pessoa,
			"perfil" => $pessoa['pessoa_foto'],
			"pessoa_idade" => $idade,
			"view" => "usuario/meus_dados", 
			"view_menu" => "includes/menu_pagina");
		}else{
			//Finalizar cadastro
			redirect('pagina/index');
			exit();
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
			"pessoa" => $pessoa,
			"perfil" => $pessoa['pessoa_foto'],
			"view" => "usuario/meus_dados_editar", 
			"view_menu" => "includes/menu_pagina");
		}else{
			//Finalizar cadastro
			$dados = array(
			"funcao" => $funcoes,
			"perfil" => $pessoa['pessoa_foto'],
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
						"pessoa_obs" => $this->input->post("observacao"),
						"pessoa_contato" => $this->input->post("contato"),
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
						"pessoa_obs" => $this->input->post("observacao"),
						"pessoa_contato" => $this->input->post("contato"),
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
											$status_banda = $this->funcao_status_banda($funcao['Funcoes_funcao_id'], $funcao['Pessoas_pessoa_id']);
											//Verifica se a funcao da pessoa é ADM de alguma banda, em aberto
											if($status_banda){
												foreach($status_banda as $lista){
													if($lista['administrador'] == '1' && $lista['integrante_status'] == '5'){
														$alerta = array(
														'class' => 'danger',
														'mensagem' => 'Atenção! Você é administrador ativo na banda "'.$lista['banda_nome'].'" com a função de "'.$lista['funcao_nome'].'". Por favor, desative a banda ou transfira seu cargo de administrador para outro integrante, para poder desabilitar essa função nos seus dados.<br>'.validation_errors() 
														);
													}else{
														$atividade_banda = $this->atividades_aberto_integrante($lista['integrante_id']);
														//Verifica se existe alguma atividade em aberto na banda para essa função
														if($atividade_banda){
															$alerta = array(
															'class' => 'danger',
															'mensagem' => 'Atenção! A função '.$atividade_banda[0]['funcao_nome'].' possui uma atividade em aberto, por favor finalize a atividade para poder desativar essa função.<br>'.validation_errors() 
															);
														}else{
															//desativa a função do usuario
											    			$desativar = $this->Pessoas->update_disponibilidade_funcao($dados_funcao);
														}
													}
												}
											}else{
												//desativa a função do usuario
											    $desativar = $this->Pessoas->update_disponibilidade_funcao($dados_funcao);
											}
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
						"perfil" => $pessoa['pessoa_foto'],
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

	//retorna o status da pessoa na banda
	public function funcao_status_banda($funcao, $pessoa)
	{
		$this->load->model('Pessoas');
		$pessoa = $this->Pessoas->get_pessoa_status_banda($funcao, $pessoa);
		return $pessoa;
	}

	//retorna as atividades em aberto do integrante
	public function atividades_aberto_integrante($id_integrante)
	{
		$this->load->model('Atividades');
		$atividades = $this->Atividades->get_atividade_aberto_integrante($id_integrante);
		return $atividades;
	}

	public function dados()
	{
		//Verifica se a pessoa logada possui dados
		$pessoa_logado = $this->dados_pessoa_logada();
		if($pessoa_logado)
		{
			//busca o id da pessoa que irá ser consultado
			$id = $_GET['pessoa_id'];
			$this->load->model('Pessoas');
			$pessoa = $this->Pessoas->get_pessoa($id);
			if($pessoa)
			{	
				if($pessoa['pessoa_id'] == $pessoa_logado['pessoa_id'])
				{
					redirect('pessoa/meusdados');
					exit();
				}
				//Busca as funções que o usuario consultado, possui em aberto
				$funcao = $this->Pessoas->get_pessoas_funcoes_ativo($id);
				$idade = $this->calcula_idade($pessoa['pessoa_nascimento']);
				//busca as atividades em aberto da pessoa logada, como administrador
				$this->load->model('Atividades');
				$pessoa_atividades_aberto = $this->Atividades->get_pessoa_atividade_em_aberto_administrador($pessoa_logado['pessoa_id']);
				//busca as bandas em atividade da pessoa logada, como administrador
				$this->load->model('Pessoas');
				$pessoa_bandas_adm = $this->Pessoas->get_pessoa_banda_em_aberto_administrador($pessoa_logado['pessoa_id']);
				//consulta as atividades em aberto da pessoa consultada
				$participa = $this->Atividades->get_pessoa_atividade_em_aberto($pessoa['pessoa_id']);
				//consulta as atividades pendentes da pessoa consultada
				$pendente = $this->Atividades->get_pessoa_atividade_pendente($pessoa['pessoa_id']);
				//consulta as atividades recusadas da pessoa consultada
				$recusado = $this->Atividades->get_pessoa_atividade_recusado($pessoa['pessoa_id']);
				//retorna uma lista com as atividades que as duas pessoas tem em comum
				$pendente_completo = $this->Atividades->get_pessoa_atividade_pendente_completo($pessoa['pessoa_id']);
				
				$atividade_participa = "";
				$atividade_pendente = "";
				$atividade_recusada = "";

				for($i=0;$i<count($pessoa_atividades_aberto);$i++)
				{
					//Atividades em aberto
					for($a=0;$a<count($participa);$a++)
					{
						if($pessoa_atividades_aberto[$i]['atividade_id'] === $participa[$a]['atividade_id']){
							$atividade_participa = $atividade_participa.','.$participa[$a]['atividade_id']; 
						}
					}
					//Atividades pendentes
					for($a=0;$a<count($pendente);$a++)
					{
						if($pessoa_atividades_aberto[$i]['atividade_id'] === $pendente[$a]['atividade_id']){
							$atividade_pendente = $atividade_pendente.','.$pendente[$a]['atividade_id']; 
						}
					}
					//Atividades recusadas
					for($a=0;$a<count($recusado);$a++)
					{
						if($pessoa_atividades_aberto[$i]['atividade_id'] === $recusado[$a]['atividade_id']){
							$atividade_recusada = $atividade_recusada.','.$recusado[$a]['atividade_id']; 
						}
					}
				}

				if($funcao)
				{
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa_logado,
					"pessoa_idade" => $idade,
					"funcao" => $funcao,
					"atividade" => $pessoa_atividades_aberto,
					"participa" => $atividade_participa,
					"banda_adm" => $pessoa_bandas_adm,
					"perfil" => $pessoa_logado['pessoa_foto'],
					"pendente" => $atividade_pendente,
					"lista_pendente" => $pendente,
					"pendente_completo" => $pendente_completo,
					"recusado" => $atividade_recusada,
					"view" => "usuario/users_dados", 
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
				}else{
					$dados = array(
					"dados" => $pessoa,
					"funcao" => $funcao,
					"pessoa_idade" => $idade,
					"atividade" => $pessoa_atividades_aberto,
					"pessoa" => $pessoa_logado,
					"perfil" => $pessoa_logado['pessoa_foto'],
					"view" => "usuario/users_dados", 
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
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

	public function salvar_foto()
	{
		$dados = json_decode($_POST['dado']);
		if($dados->pessoa_foto){
			$dados_foto = array(
				"pessoa_id" => $dados->pessoa_id,
				"pessoa_foto" => $dados->pessoa_foto
			);
		}else{
			$ini_filename = $dados->img; // path da imagem
			$imagem = imagecreatefromjpeg($ini_filename); // criando instancia jpeg
			$diretorio = getcwd();
			chmod(base_url('public/imagens/perfil'), 0777);
			//Gera um nome unico para a foto salva
			$caracteres = 30;
			$md5 = substr(md5(uniqid(rand(), true)),0,$caracteres);
			imagejpeg($imagem, $diretorio.'/public/imagens/perfil/'.$dados->pessoa_id.'_'.$md5.'.jpg', 100); // salvando nova instancia no caminho apontado

			//Salva o nome da foto no banco de dados
			$md5 = base_url('public/imagens/perfil/'.$dados->pessoa_id.'_'.$md5.'.jpg');
			$dados_foto = array(
				"pessoa_id" => $dados->pessoa_id,
				"pessoa_foto" => $md5
			);
		}
	$this->load->model('Pessoas');
	$editou = $this->Pessoas->update($dados_foto);
	}
	//Mostra os músicos e bandas através do mapa
	public function users()
	{
		$pessoa = $this->dados_pessoa_logada();
		if($pessoa)
		{
			$this->load->model('Funcoes');
			$funcao = $this->Funcoes->get_funcoes(); 

			$this->load->model('Generos');
			$genero = $this->Generos->get_generos(); 

			$dados = array(
			"pessoa" => $pessoa,
			"funcao" => $funcao,
			"genero" => $genero,
			"view" => "usuario/users", 
			"perfil" => $pessoa['pessoa_foto'],
			"view_menu" => "includes/menu_pagina",
			"usuario_email" => $_SESSION['email']);
		}else{
			//Finalizar cadastro
			redirect('pagina/index');
			exit();
		}
		
		$this->load->view('template', $dados);	
	}

	public function notificacao()
	{
		$atividade_id = $_GET['atividade'];
		if($atividade_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Atividades');
				$atividade = $this->Atividades->get_pessoa_atividade_completo($atividade_id, $pessoa['pessoa_id']);
				$administrador = $this->Atividades->get_administrador_atividade($atividade_id);

				if($atividade){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "usuario/notificacao_atividade", 
					"atividade" => $atividade,
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

	public function pendente()
	{
		$atividade_id = $_GET['atividade'];
		if($atividade_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Atividades');
				$atividade = $this->Atividades->get_pessoa_atividade_completo($atividade_id, $pessoa['pessoa_id']);
				$administrador = $this->Atividades->get_administrador_atividade($atividade_id);

				if($atividade){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "usuario/pendente_atividade", 
					"atividade" => $atividade,
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

	public function resposta()
	{
		$atividade_id = $_GET['atividade'];
		$pessoa_id = $_GET['pessoa'];
		if($atividade_id && $pessoa_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Atividades');
				$atividade = $this->Atividades->get_pessoa_atividade_completo($atividade_id, $pessoa_id);
				$administrador = $this->Atividades->get_administrador_atividade($atividade_id);

				if($atividade){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "usuario/notificacao_resposta", 
					"atividade" => $atividade,
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

	public function salvar_longitude_latitude()
	{
		$dados = json_decode($_POST['dados']);
		$dados_pessoa = array(
			"pessoa_id" => $dados->pessoa,
			"pessoa_latitude" => $dados->latitude,
			"pessoa_longitude" => $dados->longitude
		);
		$this->load->model('Pessoas');
		$alterou = $this->Pessoas->update($dados_pessoa);
		echo $alterou;
	}

	//busca os musicos por função para mostrar no mapa
	public function get_musicos_funcao_mapa()
	{
		$dados = json_decode($_POST['dados']);
		$this->load->model('Pessoas');
		$musicos = $this->Pessoas->get_localizacao_funcao_ativo_pessoas($dados->funcao);
		echo json_encode($musicos);
	}
}