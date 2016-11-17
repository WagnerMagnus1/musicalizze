<?php class Integrantes extends CI_Model
{
	public function cadastrar($dados)
	{
		$this->db->insert('Integrantes',$dados);
		$insert_id = $this->db->insert_id();
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function inserir_Integrantes_Atividades($dados)
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

	public function deletar_integrante_atividade($id)
	{
		$this->db->where('integrante_atividade_id', $id);
		$this->db->delete('Integrantes_Atividades');
	}

	public function update($dados)
	{
		$this->db->where('integrante_id', $dados['integrante_id']);
		$this->db->update('Integrantes', $dados);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function update_Integrantes_Atividades($dados)
	{
		$this->db->where('integrante_atividade_id', $dados['integrante_atividade_id']);
		$this->db->update('Integrantes_Atividades', $dados);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}
	//Faz o update de varias função distribuidas no sistema, porém mudar o seu nome exigirá localizar e trocar em todos os lugares
	public function update_Integrantes_Atividades_cancelado($dados)
	{
		$this->db->where('Integrantes_integrante_id', $dados['Integrantes_integrante_id']);
		$this->db->where('Atividades_atividade_id', $dados['Atividades_atividade_id']);
		$this->db->update('Integrantes_Atividades', $dados);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}
    //Update apenas pela chave primária 
	public function update_Integrantes_Atividades_completo($dados)
	{
		$this->db->where('integrante_atividade_id', $dados['integrante_atividade_id']);
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
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa);

		$integrante = $this->db->get();

		if($integrante->num_rows())
		{	
			return $integrante->result_array();
		}else{
			return false;
		}
	}

	//Busca as Bandas que o usuario participa atualmente, juntamente com as informações como Nome da banda, localização e foto
	public function get_pessoa_Bandas_ativo($pessoa_id)
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

	//Busca as Bandas que participam da atividade 
	public function get_Bandas_Atividades($atividade_id)
	{
		$this->db->select('banda_id,banda_nome,banda_foto');
		$this->db->from('Integrantes');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->group_by('banda_id');
		$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
		$this->db->where(array('Integrantes_Atividades.Atividades_atividade_id' => $atividade_id));


		$banda = $this->db->get();

		if($banda->num_rows())
		{	
			return $banda->result_array();
		}else{
			return false;
		}
	}

	//Busca simples sobre os Integrantes da banda, juntamente com as suas respectivas funções
	public function get_Integrantes_banda_generos($banda_id)
	{
		$this->db->select('pessoa_nome, pessoa_id, pessoa_foto, funcao_id, funcao_nome, integrante_id');
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
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa);

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
		$this->db->select('Pessoas_Funcoes_Pessoas_pessoa_id, banda_nome, funcao_id, funcao_nome, integrante_id, Bandas_banda_id, integrante_administrador, integrante_status');
		$this->db->from('Integrantes');
		$this->db->join('Funcoes', 'Integrantes.Pessoas_Funcoes_Funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Funcoes_funcao_id' => $funcao));
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		$retorno = $this->db->get();

		if($retorno->num_rows())
		{	
			return $retorno->result_array();
		}else{
			return false;
		}
	}

	//busca todas as Bandas que a pessoa é ADM ATIVA
	public function get_pessoa_banda_ativo_administrador($pessoa)
	{
		$this->db->select('banda_id, banda_nome, integrante_id, pessoa_nome, pessoa_id');
		$this->db->from('Integrantes');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Pessoas', 'Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id = Pessoas.pessoa_id');
		$this->db->where(array('integrante_administrador' => '1'));
		$this->db->where(array('integrante_status' => '5'));
		$this->db->where(array('banda_status' => '1'));
		$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
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
		$this->db->from('Integrantes');
		$this->db->where(array('Bandas_banda_id' => $banda));
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

	//retorna as respostas as notificações de Bandas
	public function get_integrante_resposta($banda_id)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome,integrante_status,integrante_justificativa');
		$this->db->from('Integrantes');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->where(array('Bandas_banda_id' => $banda_id));
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
		$this->db->from('Integrantes');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->where(array('Bandas_banda_id' => $banda_id));
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
		$this->db->from('Integrantes');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
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

	//Retorna todas as Pessoas vinculadas a banda com resposta para o ADM verificar
	public function get_integrante_visualizacao_adm($id_banda)
	{
	$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome,integrante_status,integrante_justificativa, integrante_administrador');
	$this->db->from('Integrantes');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
	$this->db->where(array('Atividades.atividade_id' => $id_banda));
	$this->db->where(array('Integrantes.integrante_visualizacao' => '1'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna as Bandas PENDENTES da pessoa
    public function get_pessoa_banda_pendente($pessoa)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome, integrante_id');
		$this->db->from('Integrantes');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('Integrantes.integrante_status' => '1'));
		$Bandas = $this->db->get();

	    if($Bandas->num_rows())
	    {    
	        return $Bandas->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna solicitação pendente da PESSOA
    public function get_pessoa_banda_pendente_todos($pessoa)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome, integrante_id,integrante_status');
		$this->db->from('Integrantes');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		$Bandas = $this->db->get();

	    if($Bandas->num_rows())
	    {    
	        return $Bandas->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna solicitação pendente da banda para a pessoa participar
    public function get_aguarda_aprovacao_pessoa_para_banda($pessoa)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome, integrante_id,integrante_status');
		$this->db->from('Integrantes');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('Integrantes.integrante_status' => '1'));
		$Bandas = $this->db->get();

	    if($Bandas->num_rows())
	    {    
	        return $Bandas->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna as Bandas RECUSADAS da pessoa
    public function get_pessoa_banda_recusado($pessoa)
	{
		$this->db->select('banda_id,banda_nome,pessoa_id,pessoa_nome');
		$this->db->from('Integrantes');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('Integrantes.integrante_status' => '4'));
		$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna os dados do adm da banda
    public function get_administrador_banda($banda)
	{
		$this->db->select('Bandas_banda_id,pessoa_id,pessoa_nome, integrante_administrador');
		$this->db->from('Integrantes');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->where(array('Integrantes.Bandas_banda_id' => $banda));
		$this->db->where(array('Integrantes.integrante_administrador' => '1'));
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
		$this->db->from('Integrantes');
		$this->db->where(array('Bandas_banda_id' => $banda));
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
		$this->db->from('Integrantes');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Funcoes', 'Integrantes.Pessoas_Funcoes_Funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('Integrantes.Bandas_banda_id' => $banda));
		$this->db->where(array('Pessoas.pessoa_id' => $pessoa));
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
		$this->db->from('Integrantes');
		$this->db->where(array('Integrantes.integrante_id' => $id_integrante));
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
		$this->db->from('Integrantes_Atividades');
		$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_Atividades.Integrantes_integrante_id');
		$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
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
		$this->db->from('Integrantes_Atividades');
		$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_Atividades.Integrantes_integrante_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
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

	//Retorna os dados da tabela Integrantes_Atividades apartir do id_integrante
	public function get_integrante_atividade_cancelado_integrante_atividade($id_pessoa, $id_integrante_atividade)
	{
		$this->db->from('Integrantes_Atividades');
		$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_Atividades.Integrantes_integrante_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
		$this->db->where(array('integrante_atividade_id' => $id_integrante_atividade));
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
		$this->db->from('Integrantes');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Funcoes', 'Integrantes.Pessoas_Funcoes_Funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('Integrantes.Bandas_banda_id' => $banda));
		$this->db->where(array('Pessoas.pessoa_id' => $pessoa));
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
		$this->db->from('Integrantes');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Funcoes', 'Integrantes.Pessoas_Funcoes_Funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('Integrantes.Bandas_banda_id' => $banda));
		$this->db->where(array('Pessoas.pessoa_id' => $pessoa));
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
		$this->db->from('Integrantes');
		$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		$this->db->where(array('Integrantes.Bandas_banda_id' => $banda));
		$this->db->where(array('Integrantes_Atividades.Atividades_atividade_id' => $atividade));
		$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Verifica se a pessoa é adm ativo da banda
    public function get_pessoa_banda_adm($banda, $pessoa)
	{
		$this->db->select('integrante_status, integrante_id');
		$this->db->from('Integrantes');
		$this->db->where(array('Bandas_banda_id' => $banda));
		$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
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

	//Retorna todos os Integrantes ativos da banda
    public function get_Integrantes_ativo_banda($banda)
	{
		$this->db->from('Integrantes');
		$this->db->where(array('Bandas_banda_id' => $banda));
		$this->db->where(array('integrante_status' => '5'));
		$retorno = $this->db->get();

	    if($retorno->num_rows())
	    {    
	        return $retorno->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os Integrantes ativos da banda
    public function get_Integrantes_ativo_banda_integrante($id_integrante)
	{
		$this->db->from('Integrantes');
		$this->db->where(array('integrante_id' => $id_integrante));
		$this->db->where(array('integrante_status' => '5'));
		$retorno = $this->db->get();

	    if($retorno->num_rows())
	    {    
	        return $retorno->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos as Atividades novas de cada integrante da banda
    public function get_Atividades_novas_integrante($pessoa)
	{
		$this->db->select('Pessoas_Funcoes_Pessoas_pessoa_id,banda_id, banda_nome');
		$this->db->from('Integrantes');
		$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
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
		$this->db->from('Integrantes');
		$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
		$this->db->where(array('Integrantes.Bandas_banda_id' => $banda));
		$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa));
		//$this->db->where(array('integrante_status' => '5'));
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

	//Retorna o status das Atividades da banda
	public function get_Atividades_banda($banda, $status)
	{
		$this->db->from('Integrantes');
		$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
		$this->db->where(array('Integrantes.Bandas_banda_id' => $banda));
		$this->db->where(array('integrante_atividade_status' => $status));

		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna todas as solicitações pendentes de Atividades para a banda
	public function get_Atividades_pendentes_banda($pessoa_id,$banda)
	{
		$this->db->from('Integrantes');
		$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
		$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
		$this->db->join('Funcoes_Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
		$this->db->where(array('Bandas_banda_id' => $banda));
		$this->db->where(array('Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa_id));
		$this->db->where(array('integrante_atividade_status' => '0'));

		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna todas as solicitações pendentes referentes a atividade passada
	public function get_atividade_pendente($id_atividade)
	{
	$this->db->from('Integrantes_Atividades');
	$this->db->where(array('Atividades_atividade_id' => $id_atividade));
	$this->db->where(array('integrante_atividade_status' => '0'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades canceladas
	public function get_pessoa_atividade_cancelado($id_pessoa)
	{
	$this->db->select('atividade_id,atividade_titulo,banda_id,banda_nome,integrante_atividade_id');
	$this->db->from('Integrantes');
	$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '0'));
	$this->db->where(array('Integrantes_Atividades.integrante_atividade_status' => '3'));
	$this->db->where(array('Integrantes_Atividades.integrante_atividade_visualizacao' => '2'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as solicitações de atividade pendentes da banda de acorodo com o id_pessoa informado
	public function get_Atividades_banda_pendente($id_pessoa)
	{
		$this->db->select('atividade_id,atividade_titulo,banda_id,banda_nome');
		$this->db->from('Integrantes');
		$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
		$this->db->where(array('integrante_atividade_status' => '0'));

		$dados = $this->db->get();

	    if($dados->num_rows())
	    {    
	        return $dados->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna se realmente a atividade esta como pendente na banda
	public function get_Atividades_banda_pessoa_pendente($id_pessoa, $id_atividade)
	{
		$this->db->from('Integrantes');
		$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
		$this->db->where(array('Atividades_atividade_id' => $id_atividade));
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

	//Retorna todos os Integrantes vinculados a Atividades com resposta para o ADM verificar
	public function get_banda_atividade_aceitas_recusadas($id_atividade)
	{
	$this->db->select('atividade_id,atividade_titulo,banda_id,banda_nome,integrante_atividade_id');
	$this->db->from('Integrantes');
	$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->where(array('Atividades_atividade_id' => $id_atividade));
	$this->db->where(array('integrante_atividade_visualizacao' => '3'));

	$Atividades = $this->db->get();

	    if($Atividades->num_rows()) 
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna a banda vinculado a atividade
	public function get_banda_atividade($atividade)
	{
	$this->db->select('Atividades_atividade_id,banda_nome, banda_id');
	$this->db->from('Integrantes');
	$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->where(array('Atividades_atividade_id' => $atividade));
	$this->db->where(array('integrante_atividade_status' => '5'));
	$this->db->group_by('Bandas_banda_id');

	$Bandas = $this->db->get();

	    if($Bandas->num_rows())
	    {    
	        return $Bandas->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna o integrante da atividade
	public function get_atividade_integrante($id_atividade)
	{
	$this->db->select('Integrantes_integrante_id');
	$this->db->from('Integrantes');
	$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->where(array('Atividades_atividade_id' => $id_atividade));
	$this->db->group_by('Bandas_banda_id');

	$Bandas = $this->db->get();

	    if($Bandas->num_rows())
	    {    
	        return $Bandas->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os Integrantes vinculados a Atividades com resposta para o ADM verificar
	public function get_banda_atividade_aceitas_recusadas_completo($integrante_atividade_id)
	{
	$this->db->from('Integrantes');
	$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->where(array('integrante_atividade_id' => $integrante_atividade_id));
	$this->db->where(array('integrante_atividade_visualizacao' => '3'));

	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna todas as Atividades finalizadas(que ja passaram do prazo), mas que permanece em aberto para o integrante da banda.
	public function get_pessoa_atividade_finalizado_aberto($id_pessoa)
	{
	$this->db->select('atividade_id,atividade_titulo,banda_id,banda_nome,integrante_atividade_id');
	$this->db->from('Integrantes');
	$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('integrante_atividade_status' => '5'));
	$this->db->where(array('atividade_status' => '2'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna todas as Atividades finalizadas(que ja passaram do prazo), mas que permanece em aberto para o integrante da banda.
	public function get_pessoa_atividade_finalizado_aberto_completo($id_pessoa, $id_integrante, $id_atividade)
	{
	$this->db->from('Integrantes');
	$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Integrantes_integrante_id = Integrantes.integrante_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('integrante_atividade_id' => $id_integrante));
	$this->db->where(array('Atividades_atividade_id' => $id_atividade));
	$this->db->where(array('integrante_atividade_status' => '5'));
	$this->db->where(array('atividade_status' => '2'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades executadas pelo integrante
	public function get_integrante_Atividades_status($id_pessoa, $data_inicio, $data_final, $status)
	{
	$this->db->from('Atividades');
	$this->db->join('Integrantes_Atividades', 'Atividades.atividade_id = Integrantes_Atividades.Atividades_atividade_id');
	$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_Atividades.Integrantes_integrante_id');
	$this->db->where(array('atividade_status' => '2'));
	$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('integrante_atividade_status' => $status));
	$this->db->where(array('Atividades.atividade_data >=' => $data_inicio));
	$this->db->where(array('Atividades.atividade_data <=' => $data_final));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades da banda no periodo e de acordo com o status informado
	public function get_banda_Atividades_status($id_banda, $data_inicio, $data_final, $status)
	{
	$this->db->from('Atividades');
	$this->db->join('Integrantes_Atividades', 'Atividades.atividade_id = Integrantes_Atividades.Atividades_atividade_id');
	$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_Atividades.Integrantes_integrante_id');
	$this->db->join('Pessoas','Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id = Pessoas.pessoa_id');
	$this->db->where(array('atividade_status' => '2'));
	$this->db->where(array('Bandas_banda_id' => $id_banda));
	$this->db->where(array('integrante_atividade_status' => $status));
	$this->db->where(array('Atividades.atividade_data >=' => $data_inicio));
	$this->db->where(array('Atividades.atividade_data <=' => $data_final));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}
	//Retorna dados para a pessoa que for inativada de qualquer banda
	public function get_integrante_inativado_banda($pessoa_id)
	{
		$this->db->select('banda_nome, integrante_id');
		$this->db->from('Integrantes');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $pessoa_id));
		$this->db->where(array('Integrantes.integrante_status' => '6'));
		$this->db->where(array('Integrantes.integrante_visualizacao' => '3'));

		$resultado = $this->db->get();

	    if($resultado->num_rows())
	    {    
	        return $resultado->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna dados para o integrante que for inativado de qualquer banda
	public function get_integrante_inativado_banda_id_integrante($id_integrante)
	{
		$this->db->from('Integrantes');
		$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
		$this->db->where(array('Integrantes.integrante_id' => $id_integrante));
		$this->db->where(array('Integrantes.integrante_status' => '6'));
		$this->db->where(array('Integrantes.integrante_visualizacao' => '3'));

		$resultado = $this->db->get();

	    if($resultado->num_rows())
	    {    
	        return $resultado->result_array();
	    }else{
	        return false;
	    }
	}
}
