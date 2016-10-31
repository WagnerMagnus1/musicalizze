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
					//Insere a foto de acordo com o gênero escolhido no formulário
					if($this->input->post("sexo") == 'Feminino'){
						$perfil = base_url('public/imagens/perfil/perfil_feminino.jpg');
					}else{
						$perfil = base_url('public/imagens/perfil/perfil.jpg');
					}

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
						"pessoa_foto" => $perfil,
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
						"pessoa_foto" => $perfil,
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
													if($lista['integrante_administrador'] == '1' && $lista['integrante_status'] == '5'){
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
		$this->load->model('Integrantes');
		$pessoa = $this->Integrantes->get_pessoa_status_banda($funcao, $pessoa);
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
				//Busca as funções que o usuario consultado, possui em ativo
				$funcao = $this->Pessoas->get_pessoas_funcoes_ativo($id);
				$idade = $this->calcula_idade($pessoa['pessoa_nascimento']);
				//busca as atividades em aberto da pessoa logada, como administrador
				$this->load->model('Atividades');
				$pessoa_atividades_aberto = $this->Atividades->get_pessoa_atividade_em_aberto_administrador($pessoa_logado['pessoa_id']);

				//busca as bandas em que a pessoa logada é administrador e ativo
				$this->load->model('Integrantes');
				$pessoa_bandas_adm = $this->Integrantes->get_pessoa_banda_ativo_administrador($pessoa_logado['pessoa_id']);
				//consulta as bandas em atividade da pessoa consultada
				$participa_band = $this->Integrantes->get_pessoa_bandas_ativo($pessoa['pessoa_id']);
				//consulta os pedidos para participar de bandas que aguardam aprovação 
				$pendente_band = $this->Integrantes->get_aguarda_aprovacao_pessoa_para_banda($pessoa['pessoa_id']);
				//consulta as atividades recusadas da pessoa consultada
				$recusado_band = $this->Integrantes->get_pessoa_banda_recusado($pessoa['pessoa_id']);

				//consulta as atividades em aberto da pessoa consultada
				$participa = $this->Atividades->get_pessoa_atividade_em_aberto($pessoa['pessoa_id']);
				//consulta as atividades pendentes da pessoa consultada
				$pendente = $this->Atividades->get_pessoa_atividade_pendente($pessoa['pessoa_id']);
				//consulta as atividades recusadas da pessoa consultada
				$recusado = $this->Atividades->get_pessoa_atividade_recusado($pessoa['pessoa_id']);
				//retorna uma lista com as atividades que as duas pessoas tem em comum
				$pendente_completo = $this->Atividades->get_pessoa_atividade_pendente_completo($pessoa['pessoa_id']);
				
				$participa_banda = "";
				$pendente_banda = "";
				$recusado_banda = "";
				//Concatena strings sobre a situação das BANDAS
				for($i=0;$i<count($pessoa_bandas_adm);$i++)
				{
					//Atividades em aberto
					for($a=0;$a<count($participa_band);$a++)
					{
						if($pessoa_bandas_adm[$i]['banda_id'] === $participa_band[$a]['banda_id']){
							$participa_banda = $participa_banda.','.$participa_band[$a]['banda_id']; 
						}
					}
					//Atividades pendentes
					for($a=0;$a<count($pendente_band);$a++)
					{
						if($pessoa_bandas_adm[$i]['banda_id'] === $pendente_band[$a]['banda_id']){
							if($pendente_band[$a]['integrante_status'] == '1' || $pendente_band[$a]['integrante_status'] == '0')
							$pendente_banda = $pendente_banda.','.$pendente_band[$a]['banda_id']; 
						}
					}
					//Atividades recusadas
					for($a=0;$a<count($recusado_band);$a++)
					{
						if($pessoa_bandas_adm[$i]['banda_id'] === $recusado_band[$a]['banda_id']){
							$recusado_banda = $recusado_banda.','.$recusado_band[$a]['banda_id']; 
						}
					}
				}

				$atividade_participa = "";
				$atividade_pendente = "";
				$atividade_recusada = "";
				//Concatena strings sobre a situação das ATIVIDADES
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
					"participa_banda" => $participa_banda,
					"pendente_banda" => $pendente_banda,
					"recusado_banda" => $recusado_banda,
					"perfil" => $pessoa_logado['pessoa_foto'],
					"pendente" => $atividade_pendente,
					"lista_pendente" => $pendente,
					"lista_pendente_banda" => $pendente_band,
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
				$atividade = $this->Atividades->get_pessoa_atividade_completo_status($atividade_id, $pessoa['pessoa_id'],'0');
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
                //Se a atividade existir, estiver como aberto nos dados da pessoa e como finalizado na tabela atividades, ele deixa mostrar
				if($atividade && $atividade[0]['funcao_status']=='5' && $atividade[0]['atividade_status']=='2'){
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

	public function pendente_integrante()
	{
		$atividade_id = $_GET['atividade'];
		$integrante_id = $_GET['integrante'];
		if($atividade_id && $integrante_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Integrantes');$this->load->model('Atividades');
				$atividade = $this->Integrantes->get_pessoa_atividade_finalizado_aberto_completo($pessoa['pessoa_id'], $integrante_id, $atividade_id);
				$administrador = $this->Atividades->get_administrador_atividade($atividade_id);
                //Se a atividade existir, estiver como aberto nos dados da pessoa e como finalizado na tabela atividades, ele deixa mostrar
				if($atividade && $atividade[0]['integrante_atividade_status']=='5' && $atividade[0]['atividade_status']=='2'){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "usuario/pendente_atividade_integrante", 
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
				$atividade = $this->Atividades->get_pessoa_atividade_completo_visualizacao($atividade_id, $pessoa_id,'1');
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

	public function cancelado()
	{
		$atividade_id = $_GET['atividade'];
		if($atividade_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Atividades');
				$atividade = $this->Atividades->get_pessoa_atividade_cancelado_atividade($pessoa['pessoa_id'], $atividade_id);
				$administrador = $this->Atividades->get_administrador_atividade($atividade_id);
				if($atividade){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "usuario/atividade_cancelado", 
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

	public function cancelado_banda()
	{
		$integrante_atividade = $_GET['atividade'];
		if($integrante_atividade){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Integrantes');$this->load->model('Atividades');
				$atividade = $this->Integrantes->get_integrante_atividade_cancelado_integrante_atividade($pessoa['pessoa_id'], $integrante_atividade);
				$administrador = $this->Atividades->get_administrador_atividade($atividade[0]['atividade_id']);
				if($atividade){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "usuario/atividade_cancelado_integrante", 
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
	//Mostra na tela  resposta da banda quanto a notificação de atividade enviada pelo usuario
	public function resposta_banda()
	{
		$integrante_atividade_id = $_GET['atividade'];
		$banda_id = $_GET['banda'];
		if($integrante_atividade_id && $banda_id){
			$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Atividades');$this->load->model('Integrantes');
				$atividade = $this->Integrantes->get_banda_atividade_aceitas_recusadas_completo($integrante_atividade_id);
				$administrador = $this->Atividades->get_administrador_atividade($atividade[0]['atividade_id']);

				if($atividade){
					$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"view" => "usuario/notificacao_banda_resposta_atividade", 
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

	public function visualizado_reposta_banda()
	{
		$dados = json_decode($_POST['dados']);
		$integrante = $dados->integrante;

		$this->load->model('Integrantes');
		$dados = array(
 			"integrante_id" => $integrante,
 			"integrante_visualizacao" => '0'
		);
		$visualizado = $this->Integrantes->update($dados);
		return $visualizado;
	}

	public function relatorio()
	{
		$pessoa = $this->dados_pessoa_logada();
			if($pessoa){
				$this->load->model('Integrantes');

				$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"atividade_executada" => 0,
					"atividade_nao_executada" => 0,
					"atividade_recusada" => 0,
					"atividade_propria" => 0,
					"atividade_convidado" => 0,
					"atividade_banda" => 0,
					"atividade_tipo" => 0,
					"executado" => 0,
					"view" => "usuario/relatorio", 
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']
				);
			}else{
				redirect('pagina/index');
				exit();
			}

		$this->load->view('template', $dados);	
	}

	public function relatorio_consulta()
	{
		$data_inicio = $this->input->post('data_inicio');//Pega a data inicial que o usuario selecionou
		$data_fim = $this->input->post('data_fim');//Pega a data final que o usuario selecionou
		$periodo = "<p id='semquebralinha'>De </p><h3 id='semquebralinha'>'".date('d/m/Y', strtotime($data_inicio))."'</h3> <p id='semquebralinha'> até </p> <h3 id='semquebralinha'>".date('d/m/Y', strtotime($data_fim))."'</h3>";
		$data_inicio = $data_inicio.' 00:00:00';$data_fim = $data_fim.' 23:59:59';//Informa horario inicial ao final do dia consultado, para conseguir consultar no between
		$pessoa = $this->dados_pessoa_logada();
		if($pessoa)
		{
			$atividades_executadas = $this->consulta_atividades($pessoa['pessoa_id'], $data_inicio, $data_fim, '2');//Busca todas as atividades executadas da pessoa
			$atividades_nao_executadas = $this->consulta_atividades($pessoa['pessoa_id'], $data_inicio, $data_fim, '3');//Busca todas as atividades não executadas da pessoa
			$atividades_recusadas = $this->consulta_atividades($pessoa['pessoa_id'], $data_inicio, $data_fim, '4');//Busca todas as atividades Recusadas
			//var_dump($atividades_executadas);exit();
			$atividade_tipo_completo = 0;
			if(!$atividades_executadas){
				$atividades_executadas = 0;
			}else{
				$atividade_tipo['ensaio'] = 0;
				$atividade_tipo['reuniao'] = 0;
				$atividade_tipo['show'] = 0;
				$atividade_tipo['festival'] = 0;
				$atividade_tipo['evento'] = 0;
				$atividade_tipo['outros'] = 0;
				//Classifica as atividades executadas de acordo com o seu tipo
				foreach($atividades_executadas as $lista){
					if($lista['atividade_tipo'] == 'Ensaio')
						$atividade_tipo['ensaio'] = $atividade_tipo['ensaio'] + 1;
					if($lista['atividade_tipo'] == 'Reunião')
						$atividade_tipo['reuniao'] = $atividade_tipo['reuniao'] + 1;
					if($lista['atividade_tipo'] == 'Show')
						$atividade_tipo['show'] = $atividade_tipo['show'] + 1;
					if($lista['atividade_tipo'] == 'Festival')
						$atividade_tipo['festival'] = $atividade_tipo['festival'] + 1;
					if($lista['atividade_tipo'] == 'Evento')
						$atividade_tipo['evento'] = $atividade_tipo['evento'] + 1;
					if($lista['atividade_tipo'] == 'Outros')
						$atividade_tipo['outros'] = $atividade_tipo['outros'] + 1;
				}
				$atividade_tipo_completo = $atividade_tipo;
				$atividades_executadas = count($atividades_executadas);
			}
			if(!$atividades_nao_executadas){
				$atividades_nao_executadas = 0;
			}else{
				$atividades_nao_executadas = count($atividades_nao_executadas);
			}
			if(!$atividades_recusadas){
				$atividades_recusadas = 0;
			}else{
				$atividades_recusadas = count($atividades_recusadas);
			}

			$minhas_atividades_adm = $this->consulta_minhas_atividades_finalizadas($pessoa['pessoa_id'], $data_inicio, $data_fim);//Busca as atividades executadas com sucesso, que a pessoa é o ADM
			if(!$minhas_atividades_adm){
				$minhas_atividades_adm = 0;
			}else{
				$minhas_atividades_adm = count($minhas_atividades_adm);
			}

			$atividade_convidado = $this->consulta_atividades_finalizadas_convidado($pessoa['pessoa_id'], $data_inicio, $data_fim);//Busca as atividades executadas com sucesso, que a pessoa é o convidado

			if(!$atividade_convidado){
				$atividade_convidado = 0;
			}else{
				$atividade_convidado = count($atividade_convidado);
			}

			$atividade_banda = $this->consulta_atividades_finalizadas_integrante($pessoa['pessoa_id'], $data_inicio, $data_fim);//Busca as atividades executadas com sucesso, que a pessoa é integrante
			if(!$atividade_banda){
				$atividade_banda = 0;
			}else{
				$atividade_banda = count($atividade_banda);
			}
			
			$atividades = $this->consulta_atividades($pessoa['pessoa_id'], $data_inicio, $data_fim, '2');//Busca todas as atividades executadas da pessoa
			$exe = $this->atividades_valores($atividades);//Busca as atividades executadas e seus respectivos valores para informar no gráfico

			$data_points = "";
			if($exe){
				foreach($exe as $lista){
	              $data_points = $data_points."{ label: '".$lista['atividade_titulo']."',  y: ".$lista['atividade_valor']." },";
			    }
			}

			$executado = "window.onload = function () {
		          var chart = new CanvasJS.Chart('chartContainer',
		          {
		            title:{
		              text: 'Respectivas atividades e seus valores'
		            },
		            data: [
		              {
		                type: 'column',
		                fillOpacity: 0.3, //**Try various Opacity values **//

		                dataPoints: [
		                	".$data_points."
		                ]
		                
		              }
		            ]
		          });
		          chart.render();
		        }";

			$dados = array(
					"dados" => $pessoa,
					"pessoa" => $pessoa,
					"perfil" => $pessoa['pessoa_foto'],
					"atividade_executada" => $atividades_executadas,
					"atividade_nao_executada" => $atividades_nao_executadas,
					"atividade_recusada" => $atividades_recusadas,
					"atividade_propria" => $minhas_atividades_adm,
					"atividade_convidado" => $atividade_convidado,
					"atividade_banda" => $atividade_banda,
					"atividade_tipo" => $atividade_tipo_completo,
					"periodo" => $periodo,
					"executado" => $executado,
					"view" => "usuario/relatorio", 
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']
				);
			
		}else{
			redirect('pagina/index');
			exit();
		}
	$this->load->view('template', $dados);	
	}

	public function consulta_atividades($pessoa_id, $data_inicio, $data_final, $status)
	{
		$array_resultado = null;
		//Consulta as atividades como usuario
		$this->load->model('Pessoas');
		$atividades_usuario = $this->Pessoas->get_usuario_atividades_status($pessoa_id, $data_inicio, $data_final, $status);
		//Consulta as atividades do integrante 
		$this->load->model('Integrantes');
		$atividades_integrante = $this->Integrantes->get_integrante_atividades_status($pessoa_id, $data_inicio, $data_final, $status);
		//Une as duas listas consultadas acima
		if($atividades_usuario && $atividades_integrante){
			$array_resultado = array_merge($atividades_usuario, $atividades_integrante);
		}else{
			if($atividades_usuario){
				$array_resultado = $atividades_usuario;
			}else{
				if($atividades_integrante){
					$array_resultado = $atividades_integrante;
				}
			}
		}
		return $array_resultado;
	}

	public function consulta_minhas_atividades_finalizadas($pessoa_id, $data_inicio, $data_final)
	{
		//Consulta as atividades como usuario ADM
		$this->load->model('Pessoas');
		$atividades_usuario = $this->Pessoas->get_usuario_atividades_adm($pessoa_id, $data_inicio, $data_final);//Consulta apenas as atividades que foram executadas como ADM
		return $atividades_usuario;
	}

	public function consulta_atividades_finalizadas_convidado($pessoa_id, $data_inicio, $data_final)
	{
		//Consulta as atividades como convidado
		$this->load->model('Pessoas');
		$atividades_usuario = $this->Pessoas->get_usuario_atividades_convidado($pessoa_id, $data_inicio, $data_final);//Consulta apenas as atividades que foram executadas como convidado
		$array = null;
		for($i=0;$i<count($atividades_usuario);$i++)
		{
			if($atividades_usuario[$i]['funcao_administrador'] != '1'){
				$array[$i] = $atividades_usuario[$i];
			}
		}
		return $array;
	}

	public function consulta_atividades_finalizadas_integrante($pessoa_id, $data_inicio, $data_final)
	{
		//Consulta as atividades como INTEGRANTE
		$this->load->model('Integrantes');
		$atividades_usuario = $this->Integrantes->get_integrante_atividades_status($pessoa_id, $data_inicio, $data_final, '2');//Consulta apenas as atividades que foram executadas como INTEGRANTE
		return $atividades_usuario;
	}
	//Retorna os valores e o titulo das atividades informadas
	public function atividades_valores($atividades)
	{
		$array = null;
		for($i=0;$i<count($atividades);$i++)
		{
			if(@$atividades[$i]['integrante_atividade_valor']){
				if(@$atividades[$i]['integrante_atividade_valor']){
					$array[$i] = array(
						"atividade_titulo" => $atividades[$i]['atividade_titulo'],
						"atividade_valor" => str_replace(',', '.', $atividades[$i]['integrante_atividade_valor'])
					);
				}else{
					$array[$i] = array(
						"atividade_titulo" => $atividades[$i]['atividade_titulo'],
						"atividade_valor" => '0.00'
					);
				}	
			}else{
				if(@$atividades[$i]['funcao_valor']){
					$array[$i] = array(
						"atividade_titulo" => $atividades[$i]['atividade_titulo'],
						"atividade_valor" => str_replace(',', '.', $atividades[$i]['funcao_valor'])
					);
				}else{
					$array[$i] = array(
						"atividade_titulo" => $atividades[$i]['atividade_titulo'],
						"atividade_valor" => '0.00'
					);
				}
			}
		}
	return $array;
	}
}