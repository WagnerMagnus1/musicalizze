<?php class Integrantes extends CI_Model
{
	public function cadastrar($dados)
	{
		$this->db->insert('integrantes',$dados);
		$insert_id = $this->db->insert_id();
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function inserir_integrantes_atividades($dados)
	{
		$this->db->insert('Integrantes_Atividades',$dados);
		$insert_id = $this->db->insert_id();
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function deletar_integrante($id)
	{
		$this->db->where('integrante_id', $id);
		$this->db->delete('Integrantes');
	}

	public function update($dados)
	{
		$this->db->where('integrante_id', $dados['integrante_id']);
		$this->db->update('integrantes', $dados);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function update_integrantes_atividades($dados)
	{
		$this->db->where('integrante_atividade_id', $dados['integrante_atividade_id']);
		$this->db->update('Integrantes_Atividades', $dados);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}
	//Faz o update de varias função distribuidas no sistema, porém mudar o seu nome exigirá localizar e trocar em todos os lugares
	public function update_integrantes_atividades_cancelado($dados)
	{
		$this->db->where('Integrantes_integrante_id', $dados['integrantes_integrante_id']);
		$this->db->where('Atividades_atividade_id', $dados['atividades_atividade_id']);
		$this->db->update('Integrantes_Atividades', $dados);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function get_pessoa_integrante($pessoa_id)
	{
		$this->db->from('Integrantes');
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa_id);

		$integrante = $this->db->get();

		if($integrante->num_rows())
		{	
			return $integrante->result_array();
		}else{
			return false;
		}
	}
	//Verifica se o integrante e pessoa existem
	public function get_integrante_banda($banda, $pessoa)
	{
		$this->db->from('Integrantes');
		$this->db->where('Bandas_banda_id', $banda);
		$this->db->where('pessoas_funcoes_pessoas_pessoa_id', $pessoa);

		$integrante = $this->db->get();

		if($integrante->num_rows())
		{	
			return $integrante->result_array();
		}else{
			return false;
		}
	}

	//Busca as bandas que o usuario participa atualmente, juntamente com as informações como Nome da banda, localização e foto
	public function get_pessoa_bandas_ativo($pessoa_id)
	{
		$this->db->select('integrante_id,banda_id,integrante_administrador,banda_nome,banda_foto,banda_estado,banda_cidade,Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->from('Integrantes');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa_id));
		$this->db->where(array('Integrantes.integrante_status' => '5'));

		$banda = $this->db->get();

		if($banda->num_rows())
		{	
			return $banda->result_array();
		}else{
			return false;
		}
	}

	//Busca as bandas que participam da atividade 
	public function get_bandas_atividades($atividade_id)
	{
		$this->db->select('banda_id,banda_nome,banda_foto');
		$this->db->from('Integrantes');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->group_by('banda_id');
		$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.integrantes_integrante_id = integrantes.integrante_id');
		$this->db->where(array('Integrantes_Atividades.atividades_atividade_id' => $atividade_id));


		$banda = $this->db->get();

		if($banda->num_rows())
		{	
			return $banda->result_array();
		}else{
			return false;
		}
	}

	//Busca simples sobre os integrantes da banda, juntamente com as suas respectivas funções
	public function get_integrantes_banda_generos($banda_id)
	{
		$this->db->select('pessoa_nome, pessoa_id, funcao_id, funcao_nome, integrante_id');
		$this->db->from('Integrantes');
		$this->db->join('Pessoas','Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id = Pessoas.pessoa_id');
		$this->db->join('Funcoes','Funcoes.funcao_id = Integrantes.Pessoas_Funcoes_Funcoes_funcao_id');
		$this->db->where(array('Integrantes.Bandas_banda_id' => $banda_id));
		$this->db->where(array('Integrantes.integrante_status' => '5'));

		$dados = $this->db->get();

		if($dados->num_rows())
		{	
			return $dados->result_array();
		}else{
			return false;
		}
	}

	//Retorna os dados do Integrante da banda X
	public function get_administrador_banda_pessoa($banda, $pessoa)
	{
		$this->db->from('Integrantes');
		$this->db->where('Bandas_banda_id', $banda);
		$this->db->where('integrante_id', $pessoa);

		$integrante = $this->db->get();

		if($integrante->num_rows())
		{	
			return $integrante->result_array();
		}else{
			return false;
		}
	}

	//Retorna os dados do Integrante de acordo com a função e pessoa informado
	public function get_pessoa_status_banda($funcao, $pessoa)
	{
		$this->db->select('pessoas_pessoa_id, banda_nome, funcao_id, funcao_nome, integrante_id, bandas_banda_id, integrante_administrador, integrante_status');
		$this->db->from('integrantes');
		$this->db->join('pessoas_funcoes', 'Pessoas_Funcoes.pessoas_pessoa_id = integrantes.pessoas_funcoes_pessoas_pessoa_id');
		$this->db->join('funcoes', 'Pessoas_Funcoes.funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->where(array('funcao_id' => $funcao));
		$this->db->where(array('pessoas_pessoa_id' => $pessoa));
		$retorno = $this->db->get();

		if($retorno->num_rows())
		{	
			return $retorno->result_array();
		}else{
			return false;
		}
	}

	//busca todas as bandas que a pessoa é ADM ATIVA
	public function get_pessoa_banda_ativo_administrador($pessoa)
	{
		$this->db->select('banda_id, banda_nome, integrante_id, pessoa_nome, pessoa_id');
		$this->db->from('integrantes');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('pessoas', 'integrantes.pessoas_funcoes_pessoas_pessoa_id = pessoas.pessoa_id');
		$this->db->where(array('integrante_administrador' => '1'));
		$this->db->where(array('integrante_status' => '5'));
		$this->db->where(array('banda_status' => '1'));
		$this->db->where(array('pessoas_funcoes_pessoas_pessoa_id' => $pessoa));
		$retorno = $this->db->get();

		if($retorno->num_rows())
		{	
			return $retorno->result_array();
		}else{
			return false;
		}
	}

	//retorna os dados do integrante de acordo com o status desejado, filtrando por banda e pessoa
	public function get_integrante_status($banda, $pessoa, $status)
	{
		$this->db->from('integrantes');
		$this->db->where(array('bandas_banda_id' => $banda));
		$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('integrante_status' => $status));
		$retorno = $this->db->get();

		if($retorno->num_rows())
		{	
			return $retorno->result_array();
		}else{
			return false;
		}
	}

	//retorna as respostas as notificações de bandas
	public function get_integrante_resposta($banda_id)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome,integrante_status,integrante_justificativa');
		$this->db->from('integrantes');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->where(array('bandas_banda_id' => $banda_id));
		$this->db->where(array('integrante_visualizacao' => '1'));
		$retorno = $this->db->get();

		if($retorno->num_rows())
		{	
			return $retorno->result_array();
		}else{
			return false;
		}
	}

	//retorna os pedidos de outros musicos que querem participar da banda
	public function get_integrante_pedido($banda_id)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome,integrante_status');
		$this->db->from('integrantes');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->where(array('bandas_banda_id' => $banda_id));
		$this->db->where(array('integrante_status' => '0'));

		$retorno = $this->db->get();

		if($retorno->num_rows())
		{	
			return $retorno->result_array();
		}else{
			return false;
		}
	}

	//retorna a resposta da banda quanto ao pedido do musico para participar dela
	public function get_resposta_pedido($pessoa_id)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome');
		$this->db->from('integrantes');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->where(array('pessoa_id' => $pessoa_id));
		$this->db->where(array('integrante_visualizacao' => '2'));

		$retorno = $this->db->get();

		if($retorno->num_rows())
		{	
			return $retorno->result_array();
		}else{
			return false;
		}
	}

	//Retorna todas as pessoas vinculadas a banda com resposta para o ADM verificar
	public function get_integrante_visualizacao_adm($id_banda)
	{
	$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome,integrante_status,integrante_justificativa, integrante_administrador');
	$this->db->from('integrantes');
	$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
	$this->db->where(array('Atividades.atividade_id' => $id_banda));
	$this->db->where(array('integrantes.integrante_visualizacao' => '1'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna as bandas PENDENTES da pessoa
    public function get_pessoa_banda_pendente($pessoa)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome, integrante_id');
		$this->db->from('integrantes');
		$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->where(array('integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('integrantes.integrante_status' => '1'));
		$bandas = $this->db->get();

	    if($bandas->num_rows())
	    {    
	        return $bandas->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna solicitação pendente da PESSOA
    public function get_pessoa_banda_pendente_todos($pessoa)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome, integrante_id,integrante_status');
		$this->db->from('integrantes');
		$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->where(array('integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		$bandas = $this->db->get();

	    if($bandas->num_rows())
	    {    
	        return $bandas->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna as bandas RECUSADAS da pessoa
    public function get_pessoa_banda_recusado($pessoa)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome');
		$this->db->from('integrantes');
		$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->where(array('integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('integrantes.integrante_status' => '4'));
		$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna os dados do adm da banda
    public function get_administrador_banda($banda)
	{
		$this->db->select('bandas_banda_id,pessoa_id,pessoa_nome, integrante_administrador');
		$this->db->from('integrantes');
		$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->where(array('integrantes.bandas_banda_id' => $banda));
		$banda = $this->db->get();

	    if($banda->num_rows())
	    {    
	        return $banda->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna o id_integrante do administrador da banda
    public function get_integrante_adm_banda($banda)
	{
		$this->db->from('integrantes');
		$this->db->where(array('bandas_banda_id' => $banda));
		$this->db->where(array('integrante_administrador' => '1'));
		$integrante = $this->db->get();

	    if($integrante->num_rows())
	    {    
	        return $integrante->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna os dados completos da pessoa e banda
	public function get_pessoa_banda_completo($banda, $pessoa)
	{
		$this->db->from('integrantes');
		$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('funcoes', 'integrantes.pessoas_funcoes_funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('integrantes.bandas_banda_id' => $banda));
		$this->db->where(array('pessoas.pessoa_id' => $pessoa));
		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna os dados da tabela Integrantes apartir do id_integrante
	public function get_integrante($id_integrante)
	{
		$this->db->from('integrantes');
		$this->db->where(array('integrantes.integrante_id' => $id_integrante));
		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna os dados da tabela Integrantes_Atividades apartir do id_integrante
	public function get_integrante_atividade_banda_aberto_cancelado($id_atividade)
	{
		$this->db->from('Integrantes_atividades');
		$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_atividades.Integrantes_integrante_id');
		$this->db->join('atividades', 'integrantes_atividades.atividades_atividade_id = atividades.atividade_id');
		$this->db->where(array('Atividades_atividade_id' => $id_atividade));
		$this->db->where(array('integrante_atividade_status' => '5'));
		$this->db->where(array('atividade_status' => '0'));
		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna os dados da tabela Integrantes_Atividades apartir do id_integrante
	public function get_integrante_atividade_cancelado($id_pessoa, $id_atividade)
	{
		$this->db->from('Integrantes_atividades');
		$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_atividades.Integrantes_integrante_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('atividades', 'integrantes_atividades.atividades_atividade_id = atividades.atividade_id');
		$this->db->where(array('Atividades_atividade_id' => $id_atividade));
		$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
		$this->db->where(array('integrante_atividade_status' => '3'));
		$this->db->where(array('atividade_status' => '0'));
		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna os dados completos da pessoa e banda com status determinado pela chamada
	public function get_pessoa_banda_completo_status($banda, $pessoa, $status)
	{
		$this->db->from('integrantes');
		$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('funcoes', 'integrantes.pessoas_funcoes_funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('integrantes.bandas_banda_id' => $banda));
		$this->db->where(array('pessoas.pessoa_id' => $pessoa));
		$this->db->where(array('integrante_status' => $status));
		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna os dados completos da pessoa e banda com status determinado pela chamada com visualização
	public function get_pessoa_banda_completo_status_visualizacao($banda, $pessoa, $status, $visualizacao)
	{
		$this->db->from('integrantes');
		$this->db->join('pessoas', 'pessoas.pessoa_id = integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('funcoes', 'integrantes.pessoas_funcoes_funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('integrantes.bandas_banda_id' => $banda));
		$this->db->where(array('pessoas.pessoa_id' => $pessoa));
		$this->db->where(array('integrante_status' => $status));
		$this->db->where(array('integrante_visualizacao' => $visualizacao));
		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna o status da atividade da banda
    public function get_atividade_banda_existe($atividade, $banda, $pessoa)
	{
		$this->db->select('integrante_atividade_status');
		$this->db->from('integrantes');
		$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = integrantes.integrante_id');
		$this->db->where(array('Integrantes.Pessoas_funcoes_pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('Integrantes.bandas_banda_id' => $banda));
		$this->db->where(array('integrantes_atividades.atividades_atividade_id' => $atividade));
		$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Verifica se a pessoa é adm ativo da banda
    public function get_pessoa_banda_adm($banda, $pessoa)
	{
		$this->db->select('integrante_status, integrante_id');
		$this->db->from('integrantes');
		$this->db->where(array('bandas_banda_id' => $banda));
		$this->db->where(array('pessoas_funcoes_pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('integrante_administrador' => '1'));
		$this->db->where(array('integrante_status' => '5'));
		$retorno = $this->db->get();

	    if($retorno->num_rows())
	    {    
	        return $retorno->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os integrantes ativos da banda
    public function get_integrantes_ativo_banda($banda)
	{
		$this->db->from('integrantes');
		$this->db->where(array('bandas_banda_id' => $banda));
		$this->db->where(array('integrante_status' => '5'));
		$retorno = $this->db->get();

	    if($retorno->num_rows())
	    {    
	        return $retorno->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos as atividades novas de cada integrante da banda
    public function get_atividades_novas_integrante($pessoa)
	{
		$this->db->select('pessoas_funcoes_pessoas_pessoa_id,banda_id, banda_nome');
		$this->db->from('integrantes');
		$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = integrantes.integrante_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->where(array('pessoas_funcoes_pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('integrante_atividade_status' => '5'));
		$this->db->where(array('integrante_atividade_visualizacao' => '2'));
		$retorno = $this->db->get();

	    if($retorno->num_rows())
	    {    
	        return $retorno->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna os dados completos da atividade do integrante de acordo com o status informado
	public function get_atividade_integrante_aberto_completo($banda, $pessoa, $status, $visualizacao)
	{
		$this->db->from('integrantes');
		$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = integrantes.integrante_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('atividades', 'integrantes_atividades.atividades_atividade_id = atividades.atividade_id');
		$this->db->where(array('integrantes.bandas_banda_id' => $banda));
		$this->db->where(array('pessoas_funcoes_pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('integrante_status' => '5'));
		$this->db->where(array('integrante_atividade_status' => $status));
		$this->db->where(array('integrante_atividade_visualizacao' => $visualizacao));

		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna o status das atividades da banda
	public function get_atividades_banda($banda, $status)
	{
		$this->db->from('integrantes');
		$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = integrantes.integrante_id');
		$this->db->where(array('integrantes.bandas_banda_id' => $banda));
		$this->db->where(array('integrante_atividade_status' => $status));

		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna todas as solicitações pendentes refetes a atividade passada
	public function get_atividade_pendente($id_atividade)
	{
	$this->db->from('integrantes_atividades');
	$this->db->where(array('Atividades_atividade_id' => $id_atividade));
	$this->db->where(array('integrante_atividade_status' => '0'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as atividades canceladas
	public function get_pessoa_atividade_cancelado($id_pessoa)
	{
	$this->db->select('atividade_id,atividade_titulo,banda_id,banda_nome');
	$this->db->from('integrantes');
	$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = integrantes.integrante_id');
	$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
	$this->db->join('atividades', 'integrantes_atividades.atividades_atividade_id = atividades.atividade_id');
	$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('atividades.atividade_status' => '0'));
	$this->db->where(array('integrantes_atividades.integrante_atividade_status' => '3'));
	$this->db->where(array('integrantes_atividades.integrante_atividade_visualizacao' => '2'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as solicitações de atividade pendentes da banda de acorodo com o id_pessoa informado
	public function get_atividades_banda_pendente($id_pessoa)
	{
		$this->db->select('atividade_id,atividade_titulo,banda_id,banda_nome');
		$this->db->from('integrantes');
		$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = integrantes.integrante_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('atividades', 'integrantes_atividades.atividades_atividade_id = atividades.atividade_id');
		$this->db->where(array('integrantes.pessoas_funcoes_pessoas_pessoa_id' => $id_pessoa));
		$this->db->where(array('integrante_atividade_status' => '0'));

		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna se realmente a atividade esta como pendete na banda
	public function get_atividades_banda_pessoa_pendente($id_pessoa, $id_atividade)
	{
		$this->db->from('integrantes');
		$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = integrantes.integrante_id');
		$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
		$this->db->join('atividades', 'integrantes_atividades.atividades_atividade_id = atividades.atividade_id');
		$this->db->where(array('integrantes.pessoas_funcoes_pessoas_pessoa_id' => $id_pessoa));
		$this->db->where(array('atividades_atividade_id' => $id_atividade));
		$this->db->where(array('integrante_atividade_status' => '0'));
		$this->db->where(array('integrante_administrador' => '1'));

		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os integrantes vinculados a atividades com resposta para o ADM verificar
	public function get_banda_atividade_aceitas_recusadas($id_atividade)
	{
	$this->db->select('atividade_id,atividade_titulo,banda_id,banda_nome');
	$this->db->from('integrantes');
	$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = integrantes.integrante_id');
	$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
	$this->db->join('atividades', 'integrantes_atividades.atividades_atividade_id = atividades.atividade_id');
	$this->db->where(array('Atividades_atividade_id' => $id_atividade));
	$this->db->where(array('integrante_atividade_visualizacao' => '3'));

	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os integrantes vinculados a atividades com resposta para o ADM verificar
	public function get_banda_atividade_aceitas_recusadas_completo($id_atividade)
	{
	$this->db->from('integrantes');
	$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = integrantes.integrante_id');
	$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
	$this->db->join('atividades', 'integrantes_atividades.atividades_atividade_id = atividades.atividade_id');
	$this->db->where(array('Atividades_atividade_id' => $id_atividade));
	$this->db->where(array('integrante_atividade_visualizacao' => '3'));

	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna todas as atividades finalizadas(que ja passaram do prazo), mas que permanece em aberto para o integrante da banda.
	public function get_pessoa_atividade_finalizado_aberto($id_pessoa)
	{
	$this->db->select('atividade_id,atividade_titulo,banda_id,banda_nome,integrante_atividade_id');
	$this->db->from('integrantes');
	$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = integrantes.integrante_id');
	$this->db->join('bandas', 'integrantes.bandas_banda_id = bandas.banda_id');
	$this->db->join('atividades', 'integrantes_atividades.atividades_atividade_id = atividades.atividade_id');
	$this->db->where(array('integrantes.pessoas_funcoes_pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('integrante_atividade_status' => '5'));
	$this->db->where(array('atividade_status' => '2'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna todas as atividades finalizadas(que ja passaram do prazo), mas que permanece em aberto para o integrante da banda.
	public function get_pessoa_atividade_finalizado_aberto_completo($id_pessoa, $id_integrante, $id_atividade)
	{
	$this->db->from('Integrantes');
	$this->db->join('integrantes_atividades', 'integrantes_atividades.integrantes_integrante_id = Integrantes.integrante_id');
	$this->db->join('bandas', 'Integrantes.bandas_banda_id = bandas.banda_id');
	$this->db->join('atividades', 'integrantes_atividades.atividades_atividade_id = atividades.atividade_id');
	$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('integrante_atividade_id' => $id_integrante));
	$this->db->where(array('atividades_atividade_id' => $id_atividade));
	$this->db->where(array('integrante_atividade_status' => '5'));
	$this->db->where(array('atividade_status' => '2'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}
}
