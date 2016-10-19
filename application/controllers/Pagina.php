<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Pagina extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('logado'))
		{
				redirect('dashboard/index');		
		}											
	}

	public function index()
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
			//Pagina principal do Usuario ja Cadastrado
			$pessoa_funcao = $this->Pessoas->get_pessoa_funcao($pessoa['pessoa_id']);
			$funcaoativa = $this->Pessoas->get_pessoas_funcoes_ativo($pessoa['pessoa_id']);
			$atividades_aberto = $this->retornar_atividades_aberto($pessoa['pessoa_id']);
			//Busca as lista de gêneros musicais
			$this->load->model('Generos');
			$generos = $this->Generos->get_generos();
			//Faz uma busca nas bandas que o usuario participa atualmente
			$this->load->model('Integrantes');
			$bandas_participo="";
			$bandas_participo = $this->Integrantes->get_pessoa_bandas_ativo($pessoa['pessoa_id']);

			if($pessoa_funcao)
			{		
				//Envia todos os participantes de cada atividade
				for ($i=0;$i<count($atividades_aberto);$i++) {
					$lista_integrantes[$i] = array("integrantes" => $this->Atividades->retornar_pessoas_atividade($atividades_aberto[$i]['atividade_id']));	
				}
				//Encaminha todos os dados levantados para a View Pagina/Index
				$dados = array(
				"pessoa" => $pessoa,
				"perfil" =>$pessoa['pessoa_foto'],
				"funcaoativa" => $funcaoativa,
				"atividades_aberto" => $atividades_aberto,
				"lista_integrantes" => $lista_integrantes,
				"generos" => $generos,
				"bandas_participo" => $bandas_participo,
				"view" => "pagina/index", 
				"view_menu" => "includes/menu_pagina",
				"usuario_email" => $_SESSION['email']);
			}
			
		}else{
				if($_SESSION['email'] == "admin@admin.com"){
					//Pagina do administrador
					$dados = array(
					"view" => "pagina/administrador", 
					"view_menu" => "includes/menu_pagina",
					"usuario_email" => $_SESSION['email']);
				}else{
				//Finalizar cadastro
				$dados = array(
				"funcao" => $funcoes,
				"view" => "usuario/pessoa_cadastro", 
				"view_menu" => "includes/menu_pagina",
				"usuario_email" => $_SESSION['email']);
				}
		    }

		$this->load->view('template', $dados);
	}

	public function erro_salvar()
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
			//Pagina principal do Usuario ja Cadastrado
			$pessoa_funcao = $this->Pessoas->get_pessoa_funcao($pessoa['pessoa_id']);
			$funcaoativa = $this->Pessoas->get_pessoas_funcoes_ativo($pessoa['pessoa_id']);

			$alerta = array(
			'class' => 'danger',
			'mensagem' => 'Atenção! Ocorreu um erro inesperado, por favor tente novamente ou contate o administrador. <br>'.validation_errors() 
			);
			if($pessoa_funcao)
			{
				$dados = array(
				"pessoa" => $pessoa,
				"perfil" => $pessoa['pessoa_foto'],
				"funcaoativa" => $funcaoativa,
				"alerta" => $alerta,
				"view" => "pagina/index", 
				"view_menu" => "includes/menu_pagina",
				"usuario_email" => $_SESSION['email']);
			}
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

	public function retornar_atividades_aberto($pessoa_id)
	{
			$this->load->model('Atividades');
		    //Recebo todas as atividades em que a pessoa esta marcada
		    $atividades = ""; $pendentes="";
		    $atividades = $this->Atividades->get_pessoa_atividade_em_aberto($pessoa_id);
		    if($atividades){
		    	foreach ($atividades as $a) {
		    		$venceu = $this->data_validade($a['atividade_data']);
		    		if($venceu){
		    			$dados_atividade = array(
		    				"atividade_id" => $a['atividade_id'],
		    				"atividade_status" => "2"
		    			);
		    			$altera_atividade = $this->Atividades->update($dados_atividade);//Finaliza a Atividade
		    			if($altera_atividade){
		    				$pendentes = $this->Atividades->get_atividade_pendente($dados_atividade['atividade_id']);
		    				if($pendentes){
			    				foreach($pendentes as $p)
				    			{
				    				$this->Atividades->update_solicitacao_atividade($p['Pessoas_Funcoes_Pessoas_pessoa_id'],$p['Atividades_atividade_id'], $p['Pessoas_Funcoes_Funcoes_funcao_id']); //Recusa as notificações pendentes automaticamente a cada atividade finalizada pelo tempo
				    			}
				    		}	
		    			}
		    		}
		    	}
		    }

		$atividades = $this->Atividades->get_pessoa_atividade_em_aberto($pessoa_id);
		return $atividades;
	}

	public function data_validade($data)
	{
		date_default_timezone_set('America/Sao_Paulo');
		$dt_atual		= date("Y-m-d"); // data atual
		$timestamp_dt_atual   = strtotime($dt_atual); // converte para timestamp Unix

		$dt_expira		= substr($data, 0, 10);  // data de expiração do anúncio
		$timestamp_dt_expira	= strtotime($dt_expira); // converte para timestamp Unix
		// data atual é maior que a data de expiração

		if ($timestamp_dt_atual > $timestamp_dt_expira) // true
		  return true;
		else // false
		  return false;
	}	

	public function atualiza_notificacao_atividade()
	{
		$dados = json_decode($_POST['id_notifica']);
		$this->load->model('Pessoas');
		$this->load->model('Atividades');
		$pessoa = $this->Pessoas->get_pessoa($dados->pessoa_id); //Retorna os dados da pessoa
		$falta_finalizar = $this->Atividades->get_pessoa_atividade_finalizado_aberto($pessoa['pessoa_id']); //Retorna as atividades finalizadas que falta informar se foi executado ou não

		$atividades_adm = $this->Atividades->get_pessoa_atividade_em_aberto_administrador($pessoa['pessoa_id']); //Retorna as atividades ativas e que a pessoa é administrador
		$numero = 0; $participantes_resposta = false;$administrador = false;
		for($i=0;$i<count($atividades_adm);$i++)
		{
			$lista = $this->Atividades->get_pessoa_atividade_aceitas_recusadas($atividades_adm[$i]['atividade_id']);//Retorna as respontas ás notificações enviadas
			if($lista){
				foreach($lista as $l){
					$participantes_resposta[$numero] = array('atividade_id' => $l['atividade_id'],'atividade_titulo' => $l['atividade_titulo'], 'pessoa_id' => $l['pessoa_id'], 'pessoa_nome' => $l['pessoa_nome']);
					$numero++;
				}
			}
		}

		$pendente = $this->Atividades->get_pessoa_atividade_pendente($pessoa['pessoa_id']); //Retorna as notificações de atividades pendente
		
		for($i=0;$i<count($pendente);$i++)
		{
			$administrador[$i] = $this->Atividades->get_administrador_atividade($pendente[$i]['atividade_id']);//Retorna o usuario criador das atividades pendentes
		}
		
		$pendente      = $pendente;
		$falta_finalizar     = $falta_finalizar;
		$administrador      = $administrador;
		$resposta = $participantes_resposta;

		$retorno = array($pendente, $falta_finalizar, $administrador, $resposta);
		echo json_encode($retorno);
	}

	public function atualiza_notificacao_banda()
	{
		$dados = json_decode($_POST['id_notifica']);
		$this->load->model('Pessoas');
		$this->load->model('Integrantes');
		$pessoa = $this->Pessoas->get_pessoa($dados->pessoa_id); //Retorna os dados da pessoa 

		$banda_adm = $this->Integrantes->get_pessoa_banda_ativo_administrador($pessoa['pessoa_id']); //Retorna as bandas ativas e que a pessoa é administrador

		$num2=0; $num1=0; $participantes_resposta = false;$administrador = false;$participantes_pedido = false;
		
		for($i=0;$i<count($banda_adm);$i++)
		{
			$lista1 = $this->Integrantes->get_integrante_resposta($banda_adm[$i]['banda_id']);//Retorna as respontas ás notificações enviadas
			if($lista1){
				foreach($lista1 as $l){
					$participantes_resposta[$num1] = array('banda_id' => $l['banda_id'],'banda_nome' => $l['banda_nome'], 'pessoa_id' => $l['pessoa_id'], 'pessoa_nome' => $l['pessoa_nome']);
					$num1++;
				}
			}
			$lista2 = $this->Integrantes->get_integrante_pedido($banda_adm[$i]['banda_id']);//Retorna os pedidos de outros musicos para participar da banda
			if($lista2){
				foreach($lista2 as $l){
					$participantes_pedido[$num2] = array('banda_id' => $l['banda_id'],'banda_nome' => $l['banda_nome'], 'pessoa_id' => $l['pessoa_id'], 'pessoa_nome' => $l['pessoa_nome']);
					$num2++;
				}
			}
		}

		//Retorna ao usuario, a resposta sobre o seu pedido para participar da banda
		$resposta_pedido = $this->Integrantes->get_resposta_pedido($pessoa['pessoa_id']);

		$pendente = $this->Integrantes->get_pessoa_banda_pendente($pessoa['pessoa_id']); //Retorna as notificações de bandas pendente
		for($i=0;$i<count($pendente);$i++)
		{
			$administrador[$i] = $this->Integrantes->get_administrador_banda($pendente[$i]['banda_id']);//Retorna o usuario criador das bandas pendentes
		}
		
		$pendente      = $pendente; //Mostra ao usuario as bandas que notificaram ele 
        $pedido_participacao = $participantes_pedido; //Mostra ao adm da banda, os musicos que querem participar dela
		$administrador      = $administrador; //Retorna o adm das bandas
		$resposta = $participantes_resposta; //Retorna ao adm da banda, as respostas dos usuario, sobre as notificações enviadas
		$resposta_pedido = $resposta_pedido; //Retorna ao usuario, a resposta sobre o seu pedido para participar da banda

		$retorno = array($pendente, $pedido_participacao, $administrador, $resposta, $resposta_pedido);
		echo json_encode($retorno);
	}

	public function busca()
	{
		$nome = $_POST['nome'];
		$this->load->model('Pessoas');
		$nomes = $this->Pessoas->get_nome_pessoa_parecido($nome);
		if(!$nomes){
			echo "";
		}else{
			for($i=0; $i<count($nomes);$i++){
				echo "<a style='text-decoration:none' id='mao' href='".base_url('pessoa/dados?pessoa_id=').$nomes[$i]['pessoa_id'].'&nome='.$nomes[$i]['pessoa_nome']."'><li>"."<img id='' src='".$nomes[$i]['pessoa_foto']."' />"."<div class='name'>".$nomes[$i]['pessoa_nome'].' '.$nomes[$i]['pessoa_sobrenome']."</div>"."<div class='local'>".$nomes[$i]['pessoa_estado']."</div>"."</li></a>";
	
			}
		}
	}
}