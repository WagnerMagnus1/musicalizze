<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Atividade extends CI_Controller
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

				$this->form_validation->set_rules('nometitulo', 'TITULO', 'required');			
				$this->form_validation->set_rules('explicacao', 'EXPLICAÇÃO', 'required');
				$this->form_validation->set_rules('tipo', 'TIPO', 'required');
				$this->form_validation->set_rules('data', 'DATA', 'required');
				$this->form_validation->set_rules('endereco', 'ENDEREÇO', 'required');
	    
				if($this->form_validation->run() == TRUE)
				{
						$id = $this->input->post("pessoa_id");
						$funcao = $this->input->post("funcao");

						$dados_atividade = array(
						"atividade_valor" => $this->input->post("valor"),
						"atividade_data" => $this->input->post("data"),
						"atividade_endereco" => $this->input->post("endereco"),
						"atividade_titulo" => $this->input->post("nometitulo"),
						"atividade_contrato" => $this->input->post("contrato"),
						"atividade_especificacao" => $this->input->post("explicacao"),
						"atividade_status" => '1',
						"atividade_tipo" => $this->input->post("tipo")
						);

						$this->load->model('Atividades');
						$salvou_id = $this->Atividades->cadastrar_atividade($dados_atividade); //Salva os dados da atividade criada
			
						if($salvou_id){
							$funcao_atividade = array(
								"Atividades_atividade_id" => $salvou_id,
								"Pessoas_Funcoes_Funcoes_funcao_id" => $funcao,
								"Pessoas_Funcoes_Pessoas_pessoa_id" => $id,
								"funcao_status" => '5',
								"funcao_administrador" => '1'
							);
							$salvou = $this->Atividades->salvar_funcao_atividade($funcao_atividade);
							if($salvou){
								redirect('pagina/index');
								exit();
							}else{
								$this->Atividades->deletar_atividade($salvou_id);
							}
						}
				}
		}
	redirect('pagina/erro_salvar');
	exit();	
	}

	public function notificar()
	{
		if( $this->input->post('notificaratividade') && $this->input->post('notificaratividade') == 'Notificar')
		{
				if($this->input->post('captcha')) redirect ('conta/entrar');

				$this->form_validation->set_rules('atividade', 'ATIVIDADE', 'required');			
				$this->form_validation->set_rules('funcao', 'FUNÇÃO', 'required');
				$this->form_validation->set_rules('id_pessoa', 'PESSOA', 'required');
	    
				if($this->form_validation->run() == TRUE)
				{
						$id = $this->input->post("id_pessoa");
						$funcao = $this->input->post("funcao");
						$atividade = $this->input->post("atividade");

						$funcao_atividade = array(
							"Atividades_atividade_id" => $atividade,
							"Pessoas_Funcoes_Funcoes_funcao_id" => $funcao,
							"Pessoas_Funcoes_Pessoas_pessoa_id" => $id,
							"funcao_status" => '0'
						);
						$this->load->model('Atividades');
						$salvou = $this->Atividades->salvar_funcao_atividade($funcao_atividade);
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

	public function notificarbanda()
	{
		if( $this->input->post('notificaratividade') && $this->input->post('notificaratividade') == 'Notificar')
		{
				if($this->input->post('captcha')) redirect ('conta/entrar');

				$this->form_validation->set_rules('atividade', 'ATIVIDADE', 'required');			
				$this->form_validation->set_rules('id_pessoa', 'PESSOA', 'required');
	    
				if($this->form_validation->run() == TRUE)
				{
						$banda = $this->input->post("banda");
						$atividade = $this->input->post("atividade");

						$funcao_atividade = array(
							"Atividades_atividade_id" => $atividade,
							"Pessoas_Funcoes_Funcoes_funcao_id" => $funcao,
							"Pessoas_Funcoes_Pessoas_pessoa_id" => $id,
							"funcao_status" => '0'
						);
						$this->load->model('Atividades');
						$salvou = $this->Atividades->salvar_funcao_atividade($funcao_atividade);
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

	public function editar()
	{

			$alerta = "";
			$i = 0;
			$editar = $this->input->post('editar');
			while( $editar != 'editar'.$i)
			{
				$i++;
			}
			if($editar == 'editar'.$i)
				{

					if( $this->input->post('editar'))
					{

						if($this->input->post('captcha')) redirect ('conta/entrar');

							$this->load->model('Atividades');
							$id_pessoa = $this->input->post("pessoa_id");
							$id_atividade = $this->input->post("atividade_id".$i);

							$administrador = $this->Atividades->get_pessoa_atividade_administrador($id_pessoa, $id_atividade);
							if($administrador){
								$dados_atividade = array(
									"atividade_id" => $id_atividade,
									"atividade_valor" => $this->input->post("valor".$i),
									"atividade_data" => $this->input->post("data".$i),
									"atividade_endereco" => $this->input->post("endereco".$i),
									"atividade_titulo" => $this->input->post("nometitulo".$i),
									"atividade_contrato" => $this->input->post("contrato".$i),
									"atividade_especificacao" => $this->input->post("explicacao".$i),
									"atividade_tipo" => $this->input->post("tipo".$i)
								);
								$alterou_atividade = $this->Atividades->update($dados_atividade);
								if($alterou_atividade){
									redirect('pagina/index');
									exit();
								}else{
									redirect('pagina/erro_salvar');
									exit();	
								}
							}else{
								redirect('pagina/erro_salvar');
								exit();	
							}
					}else{
						redirect('pagina/erro_salvar');
						exit();
					}
				}

		redirect('pagina/erro_salvar');
		exit();	
	}

	public function integrantes_atividade()
	{
		$dados = json_decode($_POST['dados']);
		$this->load->model('Atividades');
		$lista = $this->Atividades->retornar_pessoas_atividade($dados->id);
		$nomes = "";
		foreach ($lista as $base) {
			$lista = '|'.$base['pessoa_id'].'|'.$base['pessoa_nome'].'|'.$base['funcao_nome'].'|';
		}

		echo $lista;
	}

	public function notificacao_aceitar()
	{
		$dados = json_decode($_POST['dados']);
		$pessoa = $dados->pessoa;
		$atividade = $dados->atividade;
		$funcao = $dados->funcao;
		$status = '5';
		$visualiza = '1';

		$this->load->model('Atividades');
		$aceitou = $this->Atividades->update_funcao_atividade($pessoa,$atividade,$funcao,$status,'',$visualiza);
		return $aceitou;
	}

	public function notificacao_banda_aceitar()
	{
		$dados = json_decode($_POST['dados']);
		$integrante = $dados->integrante;
		$atividade = $dados->atividade;

		$dados = array(
			"atividades_atividade_id" => $atividade,
			"integrantes_integrante_id" => $integrante,
			"integrante_atividade_status" => '5',
			"integrante_atividade_visualizacao" => '3'
		);

		$this->load->model('Integrantes');
		$aceitou = $this->Integrantes->update_integrantes_atividades_cancelado($dados);
		if($aceitou){
		//Buscar todos os integrantes da banda e insere a atividade para cada um
			$banda = $this->Integrantes->get_integrantes_ativo_banda_integrante($integrante);
			$integrantes = $this->Integrantes->get_integrantes_ativo_banda($banda[0]['Bandas_banda_id']);
			foreach($integrantes as $lista){
					if($lista['integrante_id'] != $integrante){
						$dados = array(
						"Atividades_atividade_id" => $atividade,
						"Integrantes_integrante_id" => $lista['integrante_id'],
						"integrante_atividade_status" => '5',
						"integrante_atividade_visualizacao" => '2'
						);
						$this->Integrantes->inserir_integrantes_atividades($dados);
					}
			}
		}
		return $aceitou;
	}

	public function notificacao_banda_recusar()
	{
		$dados = json_decode($_POST['dados']);
		$integrante = $dados->integrante;
		$atividade = $dados->atividade;
		$justificativa = $dados->justificativa;

		$dados = array(
			"atividades_atividade_id" => $atividade,
			"integrantes_integrante_id" => $integrante,
			"integrante_atividade_status" => '4',
			"integrante_atividade_visualizacao" => '3',
			"integrante_atividade_justificativa" => $justificativa
		);

		$this->load->model('Integrantes');
		$recusou = $this->Integrantes->update_integrantes_atividades_cancelado($dados);
		return $recusou;
	}

	public function notificacao_recusar()
	{
		$dados = json_decode($_POST['dados']);
		$pessoa = $dados->pessoa;
		$atividade = $dados->atividade;
		$funcao = $dados->funcao;
		$justificativa = $dados->justificativa;
		if($justificativa =="")
		{
			$justificativa = "Sem Justificação.";
		}
		$status = '4';
		$visualiza = '1';

		$this->load->model('Atividades');
		$recusou = $this->Atividades->update_funcao_atividade($pessoa,$atividade,$funcao,$status,$justificativa,$visualiza);
		return $recusou;
	}

	public function nao_executado()
	{
		$dados = json_decode($_POST['dados']);
		$pessoa = $dados->pessoa;
		$atividade = $dados->atividade;
		$funcao = $dados->funcao;
		$status = "3";

		$this->load->model('Atividades');
		$alterado = $this->Atividades->atividade_finalizacao($pessoa,$atividade,$funcao,$status,null);
		return $alterado;
	}

	public function executado()
	{
		$dados = json_decode($_POST['dados']);
		$pessoa = $dados->pessoa;
		$atividade = $dados->atividade;
		$funcao = $dados->funcao;
		$status = "2";
		$valor = $dados->valor;

		$this->load->model('Atividades');
		$alterado = $this->Atividades->atividade_finalizacao($pessoa,$atividade,$funcao,$status, $valor);
		return $alterado;
	}

	public function executado_integrante()
	{
		$dados = json_decode($_POST['dados']);
		$integrante_atividade = $dados->integrante_atividade;
		$atividade = $dados->atividade;
		$integrante = $dados->integrante;
		$valor = $dados->valor;

		$dados = array(
			"integrante_atividade_id" => $integrante_atividade,
			"Atividades_atividade_id" => $atividade,
			"Integrantes_integrante_id" => $integrante,
			"integrante_atividade_status" => '2',
			"integrante_atividade_valor" => $valor
		);

		$this->load->model('Integrantes');
		$alterado = $this->Integrantes->update_integrantes_atividades_cancelado($dados);
		return $alterado;
	}

	public function executado_integrante_nao_executado()
	{
		$dados = json_decode($_POST['dados']);
		$integrante_atividade = $dados->integrante_atividade;
		$atividade = $dados->atividade;
		$integrante = $dados->integrante;

		$dados = array(
			"integrante_atividade_id" => $integrante_atividade,
			"Atividades_atividade_id" => $atividade,
			"Integrantes_integrante_id" => $integrante,
			"integrante_atividade_status" => '3'
		);

		$this->load->model('Integrantes');
		$alterado = $this->Integrantes->update_integrantes_atividades_cancelado($dados);
		return $alterado;
	}

	public function visualizado()
	{
		$dados = json_decode($_POST['dados']);
		$pessoa = $dados->pessoa;
		$atividade = $dados->atividade;
		$funcao = $dados->funcao;
		$visualiza = '0';

		$this->load->model('Atividades');
		$visualizado = $this->Atividades->update_funcao_atividade_visualizado($pessoa,$atividade,$funcao,$visualiza);
		return $visualizado;
	}

	public function visualizado_integrante()
	{
		$dados = json_decode($_POST['dados']);
		$integrante_atividade = $dados->integrante_atividade;

		$this->load->model('Integrantes');
		$dados = array(
			"integrante_atividade_id" => $integrante_atividade,
 			"integrante_atividade_visualizacao" => '0'
		);
		$visualizado = $this->Integrantes->update_integrantes_atividades_completo($dados);
		return $visualizado;
	}

	public function visualizado_reposta_banda()
	{
		$dados = json_decode($_POST['dados']);
		$integrante = $dados->integrante;
		$atividade = $dados->atividade;

		$this->load->model('Integrantes');
		$dados = array(
 			"atividades_atividade_id" => $atividade,
 			"integrantes_integrante_id" => $integrante,
 			"integrante_atividade_visualizacao" => '0'
		);
		$visualizado = $this->Integrantes->update_integrantes_atividades_cancelado($dados);
		return $visualizado;
	}

	public function cancelar()
	{
		$dados = json_decode($_POST['dados']);
		$pessoa = $dados->pessoa;
		$atividade = $dados->atividade;
		$funcao = $dados->funcao;

		$cancelou = $this->cancelar_atividade($atividade);
		if($cancelou)
		{	
			$this->atividade_nao_executada($atividade);//Altera o status da tabela Funcoes_Atividades de 'EM ABERTO' para 'ATIVIDADE NÃO EXECUTADA'
			$this->solicitacao_recusada($atividade);//Altera o status da tabela Funcoes_Atividades de 'PENDENTE' para 'ATIVIDADE RECUSADA'
			$this->atividade_nao_executada_integrante($atividade);//Altera o status da tabela Integrantes_Atividades de 'EM ABERTO' para 'ATIVIDADE NÃO EXECUTADA'
			$this->solicitacao_recusada_integrante($atividade);//Altera o status da tabela Integrantes_Atividades de 'PENDENTE' para 'ATIVIDADE RECUSADA
		}else{
			redirect('pagina/erro_salvar');
			exit();
		}
		return $cancelou;
	}

	public function cancelar_atividade($id_atividade)
	{
		$this->load->model('Atividades');
		$atividade = $this->Atividades->get_atividade($id_atividade);
		$cancelar = FALSE;
		if($atividade)
		{
			$cancelar = $this->Atividades->cancelar_atividade($id_atividade);
		}else{
			redirect('pagina/index');
			exit();
		}
		return $cancelar;	
	}
    //Altera o status da tabela Funcoes_Atividades de 'EM ABERTO' para 'ATIVIDADE NÃO EXECUTADA'
	public function atividade_nao_executada($id_atividade)
	{
		$this->load->model('Atividades');
		$lista_em_aberto = $this->Atividades->retornar_pessoas_atividade($id_atividade);
		foreach($lista_em_aberto as $lista)
		{
			$this->Atividades->update_solicitacao_atividade_nao_executado($lista['pessoa_id'], $lista['atividade_id'], $lista['funcao_id']);
		}
	}
	//Altera o status da tabela Funcoes_Atividades de 'PENDENTE' para 'ATIVIDADE RECUSADA'
	public function solicitacao_recusada($id_atividade)
	{
		$this->load->model('Atividades');
		$lista_pendente = $this->Atividades->get_pessoas_atividade_pendente($id_atividade);
		foreach($lista_pendente as $lista)
		{
			$this->Atividades->update_solicitacao_atividade($lista['pessoa_id'], $lista['atividade_id'], $lista['funcao_id']);
		}
	}
	//Altera o status da tabela Integrantes_Atividades de 'EM ABERTO' para 'ATIVIDADE NÃO EXECUTADA'
	public function atividade_nao_executada_integrante($id_atividade)
	{
		$this->load->model('Integrantes');
		$integrante = $this->Integrantes->get_integrante_atividade_banda_aberto_cancelado($id_atividade);

		foreach($integrante as $lista)
		{
			$dados = array(
				"atividades_atividade_id" => $id_atividade,
				"integrantes_integrante_id" => $lista['Integrantes_integrante_id'],
				"integrante_atividade_status" => 3,
				"integrante_atividade_visualizacao" => 2
			);
			$this->Integrantes->update_integrantes_atividades_cancelado($dados);
		}
	}
	//Altera o status da tabela Integrantes_Atividades de 'PENDENTE' para 'ATIVIDADE RECUSADA'
	public function solicitacao_recusada_integrante($id_atividade)
	{
		$this->load->model('Atividades');
		$lista_pendente = $this->Atividades->get_pessoas_atividade_pendente($id_atividade);
		foreach($lista_pendente as $lista)
		{
			$this->Atividades->update_solicitacao_atividade($lista['pessoa_id'], $lista['atividade_id'], $lista['funcao_id']);
		}
	}
	//Cancelar notificação para participar da atividade
	public function cancelarconviteatividade()
	{
		$dados = json_decode($_POST['dados']);
		$pessoa = $dados->pessoa;
		$atividade = $dados->atividade;
		$funcao = $dados->funcao;

		$cancelou = $this->cancelar_convite_atividade($pessoa, $atividade, $funcao);
		return $cancelou;
	}

	//Cancelar notificação para a banda participar da atividade
	public function cancelarconviteatividadebanda()
	{
		$dados = json_decode($_POST['dados']);
		$id_integrante = $dados->integrante_atividade_id;

		$this->load->model('Integrantes');
		$cancelou = $this->Integrantes->deletar_integrante_atividade($id_integrante);
		return $cancelou;
	}

	public function cancelar_convite_atividade($pessoa, $atividade, $funcao)
	{
		$this->load->model('Atividades');
		$cancelou = $this->Atividades->deletar_funcao_atividade($pessoa, $atividade, $funcao);
		return $cancelou;
	}
}
