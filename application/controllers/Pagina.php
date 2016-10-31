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
			$atividades_aberto = $this->retornar_atividades_aberto($pessoa['pessoa_id']);//Busca as atividades em aberto de usuarios
			$atividades_aberto_banda = $this->retornar_atividades_aberto_banda($pessoa['pessoa_id']);//Busca as atividades abertos das bandas que participa
			$aberto_banda = $this->retornar_aberto_banda($pessoa['pessoa_id']);
			$lista_funcoes = $this->atividades_funcoes($aberto_banda);//Busca as funções do usuario em cada atividade como integrante
			$lista_sem_duplicidade = $this->atividades_duplicadas($atividades_aberto, $atividades_aberto_banda);//Filtra as duas listas anteriores para não mostrar na tela duas atividades iguais (de banda e do usuario que a fez, por exemplo)
			$lista_completa = $this->organizar_datatime($lista_sem_duplicidade);//Organiza por data da atividade DESC
			
			//Abaixo, para cada atividade em aberto, é inserido o modo como as funções do usuario serão mostradas no dashboard
			$imprimir_view_funcoes = "";
				for($i=0;$i<count($lista_completa);$i++){
					for($s=0;$s<count($lista_funcoes);$s++){
						if(@$lista_completa[$i]['atividade_id'] == @$lista_funcoes[$s]['atividade']){
							@$imprimir_view_funcoes[$i] = $imprimir_view_funcoes[$i]."<p id='semquebralinha'> ".$lista_funcoes[$s]['funcao']." (<h5 id='semquebralinha'>".$lista_funcoes[$s]['banda']."</h5>)</p>";
						}
					}	
				}

			//Busca todas as bandas vinculadas a atividade em aberto
			$this->load->model('Integrantes');$numero3=0;
			$lista_integrantes_bandas=false;
			if($lista_completa){
				foreach($lista_completa as $lista)
				{	
					$lista_integrantes_bandas[$numero3] = $this->Integrantes->get_banda_atividade($lista['atividade_id']);	
					$numero3++;
				}
			}
			
			//Busca as lista de gêneros musicais
			$this->load->model('Generos');
			$generos = $this->Generos->get_generos(); 
			//Faz uma busca nas bandas que o usuario participa atualmente
			$bandas_participo="";
			$bandas_participo = $this->Integrantes->get_pessoa_bandas_ativo($pessoa['pessoa_id']);

			if($pessoa_funcao)
			{	
				$this->load->model('Atividades');	
				//Envia todos os participantes de cada atividade individual
				$lista_integrantes="";
				for ($i=0;$i<count($lista_completa);$i++) {
					$lista_integrantes[$i] = array("integrantes" => $this->Atividades->retornar_pessoas_atividade($lista_completa[$i]['atividade_id']));	
				}

				//Encaminha todos os dados levantados para a View Pagina/Index
				$dados = array(
				"pessoa" => $pessoa,
				"perfil" =>$pessoa['pessoa_foto'],
				"funcaoativa" => $funcaoativa,
				"atividades_aberto" => $lista_completa,
				"lista_integrantes" => $lista_integrantes,
				"lista_integrantes_bandas" => $lista_integrantes_bandas,
				"generos" => $generos,
				"atividade_funcao" => $imprimir_view_funcoes,
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
	//Retorna as funções do usuario na atividade
	public function atividades_funcoes($atividades_bandas)
	{
		$array="";$cont=0;
		if($atividades_bandas){
			foreach($atividades_bandas as $lista){
				$array[$cont] = array(
					"atividade" => $lista['atividade_id'],
					"funcao" => $lista['funcao_nome'],
					"banda" => $lista['banda_nome']
				);
				$cont++;
			}
		}
		return $array;
	}
	//Verifica se existe a mesma atividade nas tabelas Funcoes_Atividades e Integrantes_Atividades, resumindo os dois em apenas um retorno
	public function atividades_duplicadas($atividade_comuns, $atividades_bandas)
	{
		$lista_sem_duplicidade = false;$numero2 = 0; $numero = 0; 
        $lista_banda = false;

		if($atividade_comuns && $atividades_bandas)//Se existirem duas listas populadas
		{
			for($a=0;$a<count($atividades_bandas);$a++)
			{
				if($atividades_bandas[$a]['atividade_id'] == @$atividades_bandas[$a+1]['atividade_id'])
				{
					
				}else{
					$lista_banda[$numero2] = $this->constroi_lista_completa($atividades_bandas[$a]);//Exclui as atividades repetidas como integrante
					$numero2++;
				}
			}

			foreach($atividade_comuns as $lista)
			{
				$lista_sem_duplicidade[$numero] = $this->constroi_lista_completa($lista);//Insere todas as atividades que é a pessoa é ADM
				$numero++;
			}

			
			for($a=0;$a<count($lista_banda);$a++)
			{
				$parametro=0;
				for($b=0;$b<count($lista_sem_duplicidade);$b++)
				{
					if($lista_banda[$a]['atividade_id'] == $lista_sem_duplicidade[$b]['atividade_id'])
					{
						$parametro--;
					}else{
						$parametro++;
					}
				}
				if($parametro == $b){
					$lista_sem_duplicidade[$numero] = $this->constroi_lista_completa($lista_banda[$a]);//Insere todas as atividades que é a pessoa é INTEGRANTE
					$numero++;
				}
			}
						
			
		}else{
			if($atividade_comuns)//Insere todas as atividades de usuarios
			{
				foreach($atividade_comuns as $lista){
					$lista_sem_duplicidade[$numero] = $this->constroi_lista_completa($lista);
					$numero++;
				}
			}else{
				if($atividades_bandas)//Insere todas as atividades vinculados as bandas
				{
					foreach($atividades_bandas as $lista){
						$lista_sem_duplicidade[$numero] = $this->constroi_lista_completa($lista);
						$numero++;
					}
				}
			}
		}

		return $lista_sem_duplicidade;
	}
	//Constrói o array com os dados que serão enviados a view
	public function constroi_lista_completa($array)
	{
		if(@$array['funcao_administrador'] == '1'){
			//Aqui irá armazenar os dados da atividade do usuario ou ADM da atividade
			$lista = array(
				"funcao_administrador" => $array['funcao_administrador'],
				"atividade_id" => $array['atividade_id'],
				"atividade_valor" => $array['atividade_valor'],
				"atividade_data" => $array['atividade_data'],
				"atividade_endereco" => $array['atividade_endereco'],
				"atividade_titulo" => $array['atividade_titulo'],
				"atividade_contrato" => $array['atividade_contrato'],
				"atividade_especificacao" => $array['atividade_especificacao'],
				"atividade_tipo" => $array['atividade_tipo'],
				"funcoes_funcao_id" => $array['Funcoes_funcao_id'],
				"funcao_nome" => $array['funcao_nome'],
				"pessoa_id" => $array['pessoa_id']
			);
		}else{
			//Aqui irá armazenar os dados da atividade do integrante
			if(@$array['funcoes_funcao_id']){
				$lista = array(
				"funcao_administrador" => '0',
				"atividade_id" => $array['atividade_id'],
				"atividade_valor" => $array['atividade_valor'],
				"atividade_data" => $array['atividade_data'],
				"atividade_endereco" => $array['atividade_endereco'],
				"atividade_titulo" => $array['atividade_titulo'],
				"atividade_contrato" => $array['atividade_contrato'],
				"atividade_especificacao" => $array['atividade_especificacao'],
				"atividade_tipo" => $array['atividade_tipo'],
				"funcoes_funcao_id" => $array['funcoes_funcao_id'],
				"funcao_nome" => $array['funcao_nome'],
				"pessoa_id" => $array['pessoa_id']
			);
			}else{
				$lista = array(
				"funcao_administrador" => '0',
				"atividade_id" => $array['atividade_id'],
				"atividade_valor" => $array['atividade_valor'],
				"atividade_data" => $array['atividade_data'],
				"atividade_endereco" => $array['atividade_endereco'],
				"atividade_titulo" => $array['atividade_titulo'],
				"atividade_contrato" => $array['atividade_contrato'],
				"atividade_especificacao" => $array['atividade_especificacao'],
				"atividade_tipo" => $array['atividade_tipo'],
				"funcoes_funcao_id" => $array['Pessoas_Funcoes_Funcoes_funcao_id'],
				"funcao_nome" => $array['funcao_nome'],
				"pessoa_id" => $array['pessoa_id']
			);
			}
		}
		return $lista;
	}

	public function organizar_datatime($array)
	{
		if($array){
			function sortFunction( $a, $b ) {
		    return strtotime($a["atividade_data"]) - strtotime($b["atividade_data"]);
			}
			usort($array, "sortFunction");
		}
		return $array;
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
			$this->load->model('Integrantes');
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
				    		//Recusa as notificações de atividades pendentes para a banda
				    		$pendente_banda = $this->Integrantes->get_atividade_pendente($dados_atividade['atividade_id']);
				    		if($pendente_banda){
				    			foreach($pendente_banda as $p)
				    			{
				    				$dados = array(
				    					"atividades_atividade_id" => $p['atividades_atividade_id'],
				    					"integrantes_integrante_id" => $p['integrantes_integrante_id'],
				    					"integrante_atividade_status" => '4'
				    				);
				    				$this->Integrantes->update_integrantes_atividades_cancelado($dados); //Recusa as notificações pendentes automaticamente a cada atividade finalizada pelo tempo
				    			}
				    		}
		    			}
		    		}
		    	}
		    }

		$atividades = $this->Atividades->get_pessoa_atividade_em_aberto($pessoa_id);
		return $atividades;
	}

	public function retornar_atividades_aberto_banda($pessoa_id)
	{
			$this->load->model('Atividades');
		    //Recebo todas as atividades em que a pessoa esta marcada
		    $atividades = ""; $pendentes="";
		    $atividades = $this->Atividades->get_pessoa_atividade_em_aberto_banda_group_by($pessoa_id);

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

		$atividades = $this->Atividades->get_pessoa_atividade_em_aberto_banda_group_by($pessoa_id);
		return $atividades;
	}

	public function retornar_aberto_banda($pessoa_id)
	{
			$this->load->model('Atividades');
		    //Recebo todas as atividades em que a pessoa esta marcada
		    $atividades = ""; 
		    $atividades = $this->Atividades->get_pessoa_atividade_em_aberto_banda_no_groupby($pessoa_id);

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
		$this->load->model('Integrantes');
		$pessoa = $this->Pessoas->get_pessoa($dados->pessoa_id); //Retorna os dados da pessoa
		$falta_finalizar = $this->Atividades->get_pessoa_atividade_finalizado_aberto($pessoa['pessoa_id']); //Retorna as atividades finalizadas que falta informar se foi executado ou não pelo USUARIO
		$falta_finalizar_integrante = $this->Integrantes->get_pessoa_atividade_finalizado_aberto($pessoa['pessoa_id']);  //Retorna as atividades finalizadas que falta informar se foi executado ou não pelo INTEGRANTE
		$atividade_banda_aberto = $this->Integrantes->get_atividades_novas_integrante($pessoa['pessoa_id']); // Retorna as novas atividades da banda 
		$atividades_adm = $this->Atividades->get_pessoa_atividade_completo_administrador($pessoa['pessoa_id']); //Retorna as atividades ativas/finalizadas e que a pessoa é administrador
		$atividade_cancelada = $this->Atividades->get_pessoa_atividade_cancelado($pessoa['pessoa_id']);//Retorna as atividades canceladas
		$atividade_cancelada_banda = $this->Integrantes->get_pessoa_atividade_cancelado($pessoa['pessoa_id']);//Retorna as atividades de Integrante canceladas 
		$numero2=0;$numero = 0; $participantes_resposta = false;$administrador = false;$administrador_atividade_banda=false;$resposta_atividade_banda=false;
		for($i=0;$i<count($atividades_adm);$i++)
		{
			$lista = $this->Atividades->get_pessoa_atividade_aceitas_recusadas($atividades_adm[$i]['atividade_id']);//Retorna as respontas às notificações enviadas
			if($lista){
				foreach($lista as $l){
					if($l['funcao_status'] == '5' || $l['funcao_status'] == '4'){
						$participantes_resposta[$numero] = array('atividade_id' => $l['atividade_id'],'atividade_titulo' => $l['atividade_titulo'], 'pessoa_id' => $l['pessoa_id'], 'pessoa_nome' => $l['pessoa_nome']);
						$numero++;
					}
				}
			}
			$lista_2 = $this->Integrantes->get_banda_atividade_aceitas_recusadas($atividades_adm[$i]['atividade_id']);//Retorna as respontas às notificações de atividades enviadas para banda
			if($lista_2){
				foreach($lista_2 as $l){
					$resposta_atividade_banda[$numero2] = array('atividade_id' => $l['atividade_id'],'atividade_titulo' => $l['atividade_titulo'], 'banda_id' => $l['banda_id'], 'banda_nome' => $l['banda_nome'], 'integrante_atividade_id' => $l['integrante_atividade_id']);
					$numero2++;
				}
			}
		}

		$pendente = $this->Atividades->get_pessoa_atividade_pendente($pessoa['pessoa_id']); //Retorna as notificações de atividades pendente
		
		for($i=0;$i<count($pendente);$i++)
		{
			$administrador[$i] = $this->Atividades->get_administrador_atividade($pendente[$i]['atividade_id']);//Retorna o usuario criador das atividades pendentes
		}

		$pendente_banda = $this->Integrantes->get_atividades_banda_pendente($pessoa['pessoa_id']);// Retorna as notificações de atividades pendentes para a banda

		for($i=0;$i<count($pendente_banda);$i++)
		{
			$administrador_atividade_banda[$i] = $this->Atividades->get_administrador_atividade($pendente_banda[$i]['atividade_id']);//Retorna o usuario criador das atividades pendentes da banda
		}
		
		$pendente      = $pendente;//Retorna as notificações de atividades pendente
		$falta_finalizar     = $falta_finalizar;//Retorna as atividades finalizadas que falta informar se foi executado ou não pelo USUARIO
		$administrador      = $administrador;//Retorna o usuario criador das atividades pendentes
		$resposta = $participantes_resposta;//Retorna as respontas às notificações enviadas
		$atividade_banda = $atividade_banda_aberto;// Retorna as novas atividades da banda 
		$pendente_banda = $pendente_banda;//Retorna o usuario criador das atividades pendentes da banda
		$administrador_atividade_banda = $administrador_atividade_banda;//Retorna o usuario criador das atividades pendentes da banda
		$resposta_banda = $resposta_atividade_banda;//Retorna as respontas às notificações de atividades enviadas para banda
		$falta_finalizar_integrante = $falta_finalizar_integrante;//Retorna as atividades finalizadas que falta informar se foi executado ou não pelo INTEGRANTE
		$atividade_cancelada = $atividade_cancelada;//Retorna as atividades canceladas
		$atividade_cancelada_banda = $atividade_cancelada_banda;//Retorna as atividades de Integrante canceladas 

		$retorno = array(
			$pendente, 
			$falta_finalizar, 
			$administrador, 
			$resposta, 
			$atividade_banda, 
			$pendente_banda, 
			$administrador_atividade_banda, 
			$resposta_banda, 
			$falta_finalizar_integrante,
			$atividade_cancelada,
			$atividade_cancelada_banda
		);

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
		$integrante_inativo = $this->Integrantes->get_integrante_inativado_banda($pessoa['pessoa_id']); //Retorna ao usuario, uma notificação quando o mesmo for inativado da banda
		
		$resposta_pedido = $this->Integrantes->get_resposta_pedido($pessoa['pessoa_id']);//Retorna ao usuario, a resposta sobre o seu pedido para participar da banda

		$pendente = $this->Integrantes->get_pessoa_banda_pendente($pessoa['pessoa_id']); //Retorna as notificações de bandas pendente
		for($i=0;$i<count($pendente);$i++)
		{
			$administrador[$i] = $this->Integrantes->get_administrador_banda($pendente[$i]['banda_id']);//Retorna o usuario ADM das bandas pendentes
		}
		
		$pendente      = $pendente; //Mostra ao usuario as bandas que notificaram ele 
        $pedido_participacao = $participantes_pedido; //Mostra ao adm da banda, os musicos que querem participar dela
		$administrador      = $administrador; //Retorna o adm das bandas
		$resposta = $participantes_resposta; //Retorna ao adm da banda, as respostas dos usuario, sobre as notificações enviadas
		$resposta_pedido = $resposta_pedido; //Retorna ao usuario, a resposta sobre o seu pedido para participar da banda
		$integrante_inativo = $integrante_inativo; //Retorna ao usuario, uma notificação quando o mesmo for inativado da banda

		$retorno = array($pendente, $pedido_participacao, $administrador, $resposta, $resposta_pedido, $integrante_inativo);
		echo json_encode($retorno);
	}

	public function busca()
	{
		$pessoa = $this->dados_pessoa_logada();
		$nome = $_POST['nome'];
		$this->load->model('Pessoas');
		$this->load->model('Bandas');

		$nomes = $this->Pessoas->get_nome_pessoa_parecido($nome);
		$banda = $this->Bandas->get_nome_banda_parecido($nome);
		if(!$nomes && !$banda){
			echo "";
		}else{
			if(!$nomes){
				echo "";
			}else{
				for($i=0; $i<count($nomes);$i++){
				echo "<a style='text-decoration:none' id='mao' href='".base_url('pessoa/dados?pessoa_id=').$nomes[$i]['pessoa_id'].'&nome='.$nomes[$i]['pessoa_nome']."'><li>"."<img id='' src='".$nomes[$i]['pessoa_foto']."' />"."<div class='name'>".$nomes[$i]['pessoa_nome'].' '.$nomes[$i]['pessoa_sobrenome']."</div>"."<div class='local'>".$nomes[$i]['pessoa_estado']."</div>"."</li></a>";
	
				}
			}
			if(!$banda){
				echo "";
			}else{
				for($i=0; $i<count($banda);$i++){
				echo "<a style='text-decoration:none' id='mao' href='".base_url('banda/dados?banda=').$banda[$i]['banda_id'].'&pessoa='.$pessoa['pessoa_id']."'><li>"."<img id='' src='".$banda[$i]['banda_foto']."' />"."<div class='name'>".$banda[$i]['banda_nome']."</div>"."<div class='local'>".$banda[$i]['banda_estado']."</div>"."</li></a>";
	
				}
			}	
		}
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
}