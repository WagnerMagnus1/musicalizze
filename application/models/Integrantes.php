<?php class Integrantes extends CI_Model
{
	public function cadastrar($dados)
	{
		$this->db->insert('integrantes',$dados);
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
}
