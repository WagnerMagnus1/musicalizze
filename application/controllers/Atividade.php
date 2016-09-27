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
}
