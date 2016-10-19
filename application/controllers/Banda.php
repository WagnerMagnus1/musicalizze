<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Banda extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('logado'))
		{
				redirect('dashboard/index');		
		}										
	}

    public function salvar()
	{

		if( $this->input->post('cadastrar') && $this->input->post('cadastrar') == 'cadastrar')
		{
				if($this->input->post('captcha')) redirect ('conta/entrar');

				$this->form_validation->set_rules('nomebanda', 'TITULO', 'required');			
				$this->form_validation->set_rules('genero[]', 'GENERO', 'required');
				$this->form_validation->set_rules('telefonebanda', 'TELEFONE', 'required');
				$this->form_validation->set_rules('estado', 'ESTADO', 'required');
				$this->form_validation->set_rules('cidade', 'CIDADE', 'required');
				$this->form_validation->set_rules('funcaobanda', 'FUNCAO', 'required');
				$this->form_validation->set_rules('explicacaobanda', 'FUNCAO', 'required');
	    
				if($this->form_validation->run() == TRUE)
				{
						$pessoa_id = $this->input->post("pessoa_id");
						$funcao = $this->input->post("funcaobanda");
						$genero = $this->input->post("genero");

						$dados_banda = array(
						"banda_nome" => $this->input->post("nomebanda"),
						"banda_explicacao" => $this->input->post("explicacaobanda"),
						"banda_foto" => 'http://pingendo.github.io/pingendo-bootstrap/assets/user_placeholder.png',
						"banda_telefone" => $this->input->post("telefonebanda"),
						"banda_estado" => $this->input->post("estado"),
						"banda_cidade" => $this->input->post("cidade"),
						"banda_contato" => $this->input->post("contatobanda"),
						"banda_status" => '1'
						);	

						$cadastrou_banda = $this->cadastrar_banda($dados_banda);//cadastrar a banda
						if($cadastrou_banda){
							//Vai inserir na tabela Bandas_Generos, cada genero selecionado pelo usuario
							$i=0;
							foreach($genero as $lista){
								$dados_genero = array("Bandas_banda_id" => $cadastrou_banda, "Generos_genero_id" => $lista, "disponibilidade" => '1');
								$cadastrou_genero = $this->inserir_genero_banda($dados_genero);
								if($cadastrou_genero){
									$i++;
								}
							}
							//Se cadastrou todos os generos segue...
							if(count($genero) == $i){
								$dados_integrante = array(
									"bandas_banda_id" => $cadastrou_banda,
									"Pessoas_Funcoes_Funcoes_funcao_id" => $funcao, 
									"Pessoas_Funcoes_Pessoas_pessoa_id" => $pessoa_id,
									"integrante_administrador" => '1',
									"integrante_status" => '5'
								);

								$cadastrou_integrante = $this->cadastrar_integrante($dados_integrante); //Cadastra o integrante
								if($cadastrou_integrante){
									redirect('pagina/index');
									exit();

								}else{
									//Exclui os registros salvos anteriormente para recomeçar 
									$this->excluir_genero_banda($cadastrou_banda);
									$this->excluir_banda($cadastrou_banda);
									redirect('pagina/erro_salvar');
									exit();
								}
							}else{
								//Exclui os registros salvos anteriormente para recomeçar 
								$this->excluir_banda($cadastrou_banda);
								$this->excluir_genero_banda($cadastrou_banda);
								redirect('pagina/erro_salvar');
								exit();
							}
						}else{
							redirect('pagina/erro_salvar');
							exit();	
						}
				}
		}
		redirect('pagina/erro_salvar');
		exit();	
	}

	//Cadastrar os dados da banda
	public function cadastrar_banda($dados_banda)
	{
		$this->load->model('Bandas');
		$cadastrou = $this->Bandas->cadastrar($dados_banda);
		return $cadastrou;
	}

	//Inserir os generos/ Estilos musicais da banda
	public function inserir_genero_banda($dados)
	{
		$this->load->model('Bandas');
		$cadastrou = $this->Bandas->inserir_genero($dados);
		return $cadastrou;
	}

	//Inserir os generos/ Estilos musicais da banda
	public function excluir_genero_banda($banda_id)
	{
		$this->load->model('Bandas');
		$deletou = $this->Bandas->deletar_genero_banda($banda_id);
		return $deletou;
	}

	//Inserir os generos/ Estilos musicais da banda
	public function excluir_banda($banda_id)
	{
		$this->load->model('Bandas');
		$deletou = $this->Bandas->deletar_banda($banda_id);
		return $deletou;
	}

	//Exclui integrante
	public function excluir_integrante($integrante_id)
	{
		$this->load->model('Integrantes');
		$deletou = $this->Integrantes->deletar_integrante($integrante_id);
		return $deletou;
	}

	//Inserir integrante
	public function cadastrar_integrante($dados_integrante)
	{
		$this->load->model('Integrantes');
		$cadastrou = $this->Integrantes->cadastrar($dados_integrante);
		return $cadastrou;
	}

	public function dados()
	{
		//Verifica se a pessoa logada possui dados
		$pessoa_logado = $this->dados_pessoa_logada();
		if($pessoa_logado)
		{
			$banda = $_GET['banda'];//busca o id da banda que sera consultada
			$pessoa = $_GET['pessoa'];//busca o id da pessoa
			//Verifica se o id da pessoa é realmente de quem esta logado
			if($pessoa_logado['pessoa_id'] != $pessoa){
				redirect('pagina/index');
				exit();
			}
			$existe = $this->verifica_banda_integrante($banda, $pessoa);//Verifica vinculo de integrante e banda
			if($existe[0]['integrante_administrador'] == '1'){
				//CONSULTA AOS DADOS NO PONTO DE VISTO DO ADM DA BANDA
				$dados = $this->minhabanda($existe[0]['Bandas_banda_id']);
			}else{
				$componente = "vazio";
				if($existe[0]['Pessoas_Funcoes_Pessoas_pessoa_id'] == $pessoa_logado['pessoa_id']){
					$componente = $existe[0]['integrante_status'];
				}

				$dados_banda = $this->banda_consulta_comum($banda);//Verifica se a banda existe
				if(!$dados_banda){
					redirect('pagina/index'); //Redireciona a pagina caso a banda não exista
					exit();
				}else{
					//Busca o id do integrante da pessoa vinculado a banda
					$pedido_banda_pendente = $this->Integrantes->get_integrante_banda($banda,$pessoa);
					//Busca as funções ativas da pessoa logada
					$this->load->model('Pessoas');
					$funcoes = $this->Pessoas->get_pessoas_funcoes_ativo($pessoa_logado['pessoa_id']);
					$atividades_aberto = $this->verifica_situacao_atividades_banda($pessoa_logado, $banda);
					//Envia dados para a view
					$dados = array(
						"dados" => $pessoa,
						"pessoa" => $pessoa_logado,
						"funcoes" => $funcoes,
						"banda" => $dados_banda['banda'],
						"generos" => $dados_banda['generos'],
						"integrantes" => $dados_banda['integrantes'],
						"pedido" => $pedido_banda_pendente[0]['integrante_id'],
						"componente" => $componente,
						"atividade" => $atividades_aberto,
						"perfil" => $pessoa_logado['pessoa_foto'],
						"view" => "banda/bandas_dados", 
						"view_menu" => "includes/menu_pagina",
						"usuario_email" => $_SESSION['email']
					);
			    }
			}
	
		}else{
			redirect('conta/sair');
			exit();
		}

		$this->load->view('template', $dados);	
	}

	//Verifica situação das aitividades para convidar banda
    public function verifica_situacao_atividades_banda($pessoa_logada, $banda_id)
    {
    	//busca as atividades em aberto da pessoa logada, como administrador
		$this->load->model('Atividades');
		$pessoa_atividades_aberto = $this->Atividades->get_pessoa_atividade_em_aberto_administrador($pessoa_logada['pessoa_id']);
		/*consulta as atividades em aberto da pessoa consultada
		$participa = $this->Atividades->get_pessoa_atividade_em_aberto($pessoa['pessoa_id']);
		//consulta as atividades pendentes da pessoa consultada
		$pendente = $this->Atividades->get_pessoa_atividade_pendente($pessoa['pessoa_id']);
		//consulta as atividades recusadas da pessoa consultada
		$recusado = $this->Atividades->get_pessoa_atividade_recusado($pessoa['pessoa_id']);
		//retorna uma lista com as atividades que as duas pessoas tem em comum

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
		}*/
		return $pessoa_atividades_aberto;
    }

	public function banda_consulta_comum($banda_id)
	{
		$dados = false;
		$this->load->model('Bandas');
		$banda = $this->Bandas->get_banda($banda_id);
		if($banda){
			//Buscar os integrantes ativos da banda
			$this->load->model('Integrantes');
			$integrantes = $this->Integrantes->get_integrantes_banda_generos($banda_id);
			$generos = $this->Bandas->get_genero_banda_ativo($banda_id);
			$dados = array(
				"banda" => $banda, 
				"integrantes" => $integrantes,
				"generos" => $generos
				);
		}
		return $dados;
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
		if($dados->banda_foto){
			$dados_foto = array(
				"banda_id" => $dados->banda_id,
				"banda_foto" => $dados->banda_foto
			);
		}else{
			$ini_filename = $dados->img; // path da imagem
			$imagem = imagecreatefromjpeg($ini_filename); // criando instancia jpeg
			$diretorio = getcwd();
			chmod(base_url('public/imagens/banda'), 0777);
			//Gera um nome unico para a foto salva
			$caracteres = 30;
			$md5 = substr(md5(uniqid(rand(), true)),0,$caracteres);
			imagejpeg($imagem, $diretorio.'/public/imagens/banda/'.$dados->banda_id.'_'.$md5.'.jpg', 100); // salvando nova instancia no caminho apontado

			//Salva o nome da foto no banco de dados
			$md5 = base_url('public/imagens/banda/'.$dados->banda_id.'_'.$md5.'.jpg');
			$dados_foto = array(
				"banda_id" => $dados->banda_id,
				"banda_foto" => $md5
			);
		}
	$this->load->model('Bandas');
	$editou = $this->Bandas->update($dados_foto);
	}
	
	public function verifica_banda_integrante($banda, $integrante)
	{
		$this->load->model('Integrantes');
		$existe = $this->Integrantes->get_integrante_banda($banda, $integrante);
		return $existe;
	}
	public function minhabanda($banda)
	{
		$pessoa = $this->dados_pessoa_logada();
		   $this->load->model('Bandas');
		$banda_dados = $this->Bandas->get_banda($banda);//Busca os dados completos da banda
		$generos = $this->Bandas->get_genero_banda_ativo($banda);//Busca os generos/ Estilos ativos da banda
		   $this->load->model('Integrantes');
		$integrantes_funcoes = $this->Integrantes->get_integrantes_banda_generos($banda);//Busca os integrantes ativos da banda, juntamente com suas respectivas funções
		

		if(!$pessoa && $banda_dados && $generos && $integrantes_funcoes){redirect('pagina/index');exit();}
		if($pessoa && $banda && $generos && $integrantes_funcoes){
			//Envia dados para a view
			$dados = array(
				"pessoa" => $pessoa,
				"banda" => $banda_dados,
				"generos" => $generos,
				"integrantes" => $integrantes_funcoes,
				"perfil" => $pessoa['pessoa_foto'],
				"view" => "banda/minhabanda", 
				"view_menu" => "includes/menu_pagina");
		}else{
			redirect('pagina/index');exit();
		}

		return $dados;
	}

	public function editar()
	{
		//Verifica se a pessoa logada possui dados
		$pessoa_logado = $this->dados_pessoa_logada();
		if($pessoa_logado)
		{
			$banda = $_GET['banda'];//busca o id da banda que sera consultada
			$pessoa = $_GET['pessoa'];//busca o id da pessoa
			$existe = $this->verifica_banda_integrante($banda, $pessoa);//Verifica se integrante e banda existem

			if($existe && $pessoa_logado['pessoa_id'] == $pessoa)
			{	
				if($existe[0]['integrante_administrador'] == '1'){
					//CONSULTA AOS DADOS NO PONTO DE VISTO DO ADM DA BANDA
					$dados = $this->minhabandaeditar($existe[0]['Bandas_banda_id']);

				}else{
					//Redireciona a pagina caso o usuario não seja o ADM
                    redirect('pagina/index');
					exit();
				}
			}else{
				redirect('pagina/index');
				exit();
			}
	
		}else{
			redirect('conta/sair');
			exit();
		}

		$this->load->view('template', $dados);	
	}

	public function minhabandaeditar($banda)
	{
		$pessoa = $this->dados_pessoa_logada();
		   $this->load->model('Bandas');
		$banda_dados = $this->Bandas->get_banda($banda);//Busca os dados completos da banda
		$generos = $this->Bandas->get_genero_banda_ativo($banda);//Busca os generos/ Estilos ativos da banda
		$generos_inativos = $this->Bandas->get_genero_banda_inativo($banda);//Busca os generos/ Estilos INATIVOS da banda
		//Junta os generos ativos em uma unica string
		$string_genero_inativo ="";$string_genero="";
		if($generos){
				foreach($generos as $genero){
					$string_genero = $genero['genero_id'].','.$string_genero;
				}
			}else{
				$string_genero = "";
			}
		//Junta os generos inativos em uma unica string
		if($generos_inativos){
			foreach($generos_inativos as $genero){
				$string_genero_inativo = $genero['genero_id'].','.$string_genero_inativo;
			}
		}else{
			$string_genero_inativo = "";
		}
		//Busca as lista de gêneros musicais
		$this->load->model('Generos');
		$genero_completo = $this->Generos->get_generos();
	   $this->load->model('Integrantes');
		$integrantes_funcoes = $this->Integrantes->get_integrantes_banda_generos($banda);//Busca os integrantes ativos da banda, juntamente com suas respectivas funções
		

		if(!$pessoa && $banda_dados && $generos && $integrantes_funcoes){redirect('pagina/index');exit();}
		if($pessoa && $banda && $generos && $integrantes_funcoes){
			//Envia dados para a view
			$dados = array(
				"pessoa" => $pessoa,
				"banda" => $banda_dados,
				"generos_ativos" => $string_genero,
				"generos_inativos" => $string_genero_inativo,
				"generos_completo" => $genero_completo,
				"integrantes" => $integrantes_funcoes,
				"perfil" => $pessoa['pessoa_foto'],
				"view" => "banda/editar", 
				"view_menu" => "includes/menu_pagina");
		}else{
			redirect('pagina/index');exit();
		}

		return $dados;
	}

	public function salvar_editar()
	{
		if($this->input->post('captcha')) redirect ('conta/entrar');//Segurança contra invasores

		$this->form_validation->set_rules('banda_id', 'BANDA', 'required');
		$this->form_validation->set_rules('nome', 'NOME', 'required');	
		$this->form_validation->set_rules('explicacao', 'EXPLICAÇÃO', 'required');			
		$this->form_validation->set_rules('txttelefone', 'TELEFONE', 'required');
		$this->form_validation->set_rules('estado', 'ESTADO', 'required');
		$this->form_validation->set_rules('cidade', 'CIDADE', 'required');
		$this->form_validation->set_rules('genero[]', 'GÊNERO', 'required');

		if($this->form_validation->run() == TRUE)
		{
			if( $this->input->post('cadastrar') && $this->input->post('cadastrar') == 'cadastrar')
			{
				$banda_id = $this->input->post("banda_id");//busca o id da banda que sera consultada
				$pessoa = $this->dados_pessoa_logada(); //Busca os dados da pessoa logada

				$administrador = $this->retorna_pessoa_administrador_banda($banda_id, $pessoa['pessoa_id']);//Verifica se a pessoa logada é o administrador da banda
				if($administrador)
				{
					$dados_banda = array(
						"banda_id" => $this->input->post("banda_id"),
						"banda_nome" => $this->input->post("nome"),
						"banda_explicacao" => $this->input->post("explicacao"),
						"banda_telefone"=> $this->input->post("txttelefone"),
						"banda_estado" => $this->input->post("estado"),
						"banda_cidade" => $this->input->post("cidade"),
						"banda_contato" => $this->input->post("contato")
					);
					$editou_banda = $this->altera_dados_banda($dados_banda); //Edita os dados da banda
					$generos = $this->input->post("genero"); 
					$editou_generos_banda = $this->atualiza_generos_banda($banda_id, $generos);//Edita o vinculo aos gêneros
					redirect('banda/dados?banda='.$dados_banda['banda_id'].'&pessoa='.$pessoa['pessoa_id']);
					exit();
				}else{
					redirect('pagina/index');
					exit();
				}
			}
		}else{
			redirect('pagina/erro_salvar');
			exit();
		}

		//Envia dados para a view
		$dados = array(
			"pessoa" => $pessoa,
			"banda" => $banda_dados,
			"generos" => $generos,
			"integrantes" => $integrantes_funcoes,
			"perfil" => $pessoa['pessoa_foto'],
			"view" => "banda/minhabanda", 
			"view_menu" => "includes/menu_pagina");
		
	$this->load->view('template', $dados);
	}
	//Verifica se o usuario é o administrador da banda
	public function retorna_pessoa_administrador_banda($banda_id, $pessoa_id)
	{	
		$this->load->model('Integrantes');
		$administrador = $this->Integrantes->get_administrador_banda_pessoa($banda_id, $pessoa_id);
		return $administrador;
	}
	//Edita os dados da banda
	public function altera_dados_banda($dados_banda)
	{
		$this->load->model('Bandas');
		$alterou = $this->Bandas->update($dados_banda);
		return $alterou;
	}
	//Altera os gêneros da banda, de acordo com os que o usuario deixou selecionado
	public function atualiza_generos_banda($banda_id, $generos)
	{
		//Aqui estão todas as funções que o usuario deixou marcado no formulário
		$genero_form = $generos;
		//Todas as funcoes ja vinculadas pelo usuario no banco de dados
		$this->load->model('Bandas');
		$genero_ativo = $this->Bandas->get_genero_banda_ativo($banda_id);
		$genero_inativo = $this->Bandas->get_genero_banda_inativo($banda_id);

		if($genero_ativo){
			//desativar genero
			foreach($genero_ativo as $lista)
			{
				$remover = 0;
				for ($i=0;$i<count($genero_form);$i++)
				{
					if($lista['Generos_genero_id'] == $genero_form[$i])
					{
						//Não faz nada
					}else{
						$remover = $remover + 1;
					}
				}
				if($remover == $i)
				{
					$dados_genero = array(
					"Bandas_banda_id" => $banda_id,
					"Generos_genero_id" => $lista['Generos_genero_id'],
					"disponibilidade" => '0'
					);
					//desativar genero da banda
					$desativar = $this->Bandas->update_genero_disponibilidade($dados_genero);
				}
			}
		}
		if($genero_inativo){
			//ativar genero 
			foreach($genero_inativo as $lista)
			{
				for ($i=0;$i<count($genero_form);$i++)
				{
					if($lista['Generos_genero_id'] == $genero_form[$i])
					{
						$dados_genero = array(
						"Bandas_banda_id" => $banda_id,
						"Generos_genero_id" => $lista['Generos_genero_id'],
						"disponibilidade" => '1'
						);
						//ativa genero da banda
						$ativar = $this->Bandas->update_genero_disponibilidade($dados_genero);
					}
				}
			}
		}
		$funcao_base = $this->Bandas->get_banda_generos($banda_id); 
			//inserir novo genero
			for ($i=0;$i<count($genero_form);$i++)
			{
				$add = 0;
				for ($f=0;$f<count($funcao_base);$f++)
				{
					if($genero_form[$i] == $funcao_base[$f]['Generos_genero_id'])
					{
						
					}else{
						$add = $add + 1;
					}
				}

				if($add == $f)
				{
					$dados_genero = array(
					"Bandas_banda_id" => $banda_id,
					"Generos_genero_id" => $genero_form[$i],
					"disponibilidade" => '1'
					);

					$cadastra_funcao = $this->Bandas->inserir_genero($dados_genero);
				}
			}
	}
}
