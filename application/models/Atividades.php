<?php class Atividades extends CI_Model
{

	public function cadastrar_atividade($dados_atividade)
	{
		$this->db->insert('Atividades',$dados_atividade);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	public function deletar_atividade($id)
	{
		$this->db->where('atividade_id', $id);
		$this->db->delete('Atividades');
	}

	public function salvar_funcao_atividade($dados)
	{
		$this->db->insert('Funcoes_Atividades',$dados);
		$insert_id = $this->db->insert_id();
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function deletar_funcao_atividade($pessoa_id, $atividade_id, $funcao_id)
	{
		$this->db->where('Pessoas_Funcoes_Funcoes_funcao_id', $funcao_id);
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa_id);
		$this->db->where('Atividades_atividade_id', $atividade_id);
		$this->db->delete('Funcoes_Atividades');
	}
//Retorna todas as atividades ativas do usuario
	public function get_pessoa_atividade_em_aberto($id_pessoa)
	{
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('pessoas_funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('atividades.atividade_status' => '1'));
	$this->db->where(array('funcoes_atividades.funcao_status' => '5'));
	$this->db->order_by('atividades.atividade_data','ASC');
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as atividades pendentes do usuario
	public function get_pessoa_atividade_pendente($id_pessoa)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome');
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('pessoas_funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('atividades.atividade_status' => '1'));
	$this->db->where(array('funcoes_atividades.funcao_status' => '0'));
	$this->db->order_by('atividades.atividade_data','ASC');
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as atividades pendentes do usuario COMPLETO
	public function get_pessoa_atividade_pendente_completo($id_pessoa)
	{
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('pessoas_funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('atividades.atividade_status' => '1'));
	$this->db->where(array('funcoes_atividades.funcao_status' => '0'));
	$this->db->order_by('atividades.atividade_data','ASC');
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as atividades recusadas do usuario
	public function get_pessoa_atividade_recusado($id_pessoa)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome');
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('pessoas_funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('atividades.atividade_status' => '1'));
	$this->db->where(array('funcoes_atividades.funcao_status' => '4'));
	$this->db->order_by('atividades.atividade_data','ASC');
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as atividades finalizadas(que ja passaram do prazo), mas que permanece em aberto para o usuario.
	public function get_pessoa_atividade_finalizado_aberto($id_pessoa)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome');
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('pessoas_funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('atividades.atividade_status' => '2'));
	$this->db->where(array('funcoes_atividades.funcao_status' => '5'));
	$this->db->order_by('atividades.atividade_data','ASC');
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as atividades ativas do usuario administrador
	public function get_pessoa_atividade_em_aberto_administrador($id_pessoa)
	{
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('pessoas_funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('atividades.atividade_status' => '1'));
	$this->db->where(array('Funcoes_Atividades.funcao_administrador' => '1'));
	$this->db->order_by('atividades.atividade_data','ASC');
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as pessoas vinculadas a atividades
	public function retornar_pessoas_atividade($id_atividade)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome, funcao_nome, funcao_id, funcao_administrador');
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Atividades.atividade_id' => $id_atividade));
	$this->db->where(array('funcoes_atividades.funcao_status' => '5'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as pessoas que receberam notificação para participar da atividade, porém estão como pendente 
	public function get_pessoas_atividade_pendente($id_atividade)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome, funcao_nome, funcao_id, funcao_administrador');
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Atividades.atividade_id' => $id_atividade));
	$this->db->where(array('funcoes_atividades.funcao_status' => '0'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as pessoas vinculadas a atividades com resposta para o ADM verificar
	public function get_pessoa_atividade_aceitas_recusadas($id_atividade)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome');
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Atividades.atividade_id' => $id_atividade));
	$this->db->where(array('funcoes_atividades.funcao_visualizacao' => '1'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna a atividade vincula a pessoa administrador
	public function get_pessoa_atividade_administrador($id_pessoa, $id_atividade)
	{
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->where(array('pessoas_funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('atividade_id' => $id_atividade));
	$this->db->where(array('funcao_administrador' => '1'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna o administrador da atividade
	public function get_administrador_atividade($id_atividade)
	{
	$this->db->select('atividade_id,pessoa_id,pessoa_nome,pessoa_foto');
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('atividade_id' => $id_atividade));
	$this->db->where(array('funcao_administrador' => '1'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os dados do administrador da atividade e da atividade
	public function get_administrador_atividade_completo($id_atividade)
	{
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('atividade_id' => $id_atividade));
	$this->db->where(array('funcao_administrador' => '1'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os dados da pessoa e da atividade
	public function get_pessoa_atividade_completo($id_atividade, $id_pessoa)
	{
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('atividade_id' => $id_atividade));
	$this->db->where(array('pessoa_id' => $id_pessoa));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna as atividades em aberto de uma determinada função da pessoa
	public function get_atividade_aberto_funcao_pessoa($id_pessoa, $id_funcao)
	{
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Pessoas_Funcoes_Funcoes_funcao_id' => $id_funcao));
	$this->db->where(array('atividade_status' => '1'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Informa as atividades em aberto do integrante 
	public function get_atividade_aberto_integrante($integrante)
	{
		$this->db->from('integrantes');
		$this->db->join('atividades_bandas', 'integrantes.integrante_id = atividades_bandas.integrantes_bandas_integrantes_integrante_id');
		$this->db->join('atividades', 'atividades.atividade_id = atividades_bandas.atividades_atividade_id');
		$this->db->join('funcoes', 'Pessoas_Funcoes.funcoes_funcao_id = integrantes.pessoas_funcoes_funcoes_funcao_id');
		$this->db->where(array('integrante_id' => $integrante));
		$this->db->where(array('atividade_status' => '1'));
		$retorno = $this->db->get();

		if($retorno->num_rows())
		{	
			return $retorno->result_array();
		}else{
			return false;
		}
	}

	//Salva as alterações que o usuario inseriu na atividade
	public function update($dados_atividade)
	{
		$this->db->where('atividade_id', $dados_atividade['atividade_id']);
		$this->db->update('Atividades', $dados_atividade);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	//Cancela a Atividade
	public function cancelar_atividade($id_atividade)
	{
		$this->db->set('atividade_status', '0');
		$this->db->where('atividade_id', $id_atividade);
		$this->db->update('Atividades');
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	//Altera os dados de da funcao_atividade, de acordo com a resposta do usuario 
	public function update_funcao_atividade($pessoa_id, $atividade_id, $funcao_id, $status, $justificativa,$visualiza)
	{
		$this->db->set('funcao_status', $status);
		$this->db->set('funcao_justificativa', $justificativa);
		$this->db->set('funcao_visualizacao', $visualiza);
		$this->db->where('Pessoas_Funcoes_Funcoes_funcao_id', $funcao_id);
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa_id);
		$this->db->where('Atividades_atividade_id', $atividade_id);
		$this->db->update('Funcoes_Atividades');
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	//Altera os dados para visulizado pelo ADM
	public function update_funcao_atividade_visualizado($pessoa_id, $atividade_id, $funcao_id,$visualiza)
	{
		$this->db->set('funcao_visualizacao', $visualiza);
		$this->db->where('Pessoas_Funcoes_Funcoes_funcao_id', $funcao_id);
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa_id);
		$this->db->where('Atividades_atividade_id', $atividade_id);
		$this->db->update('Funcoes_Atividades');
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	//Altera solicitação da atividade para não aceita
	public function update_solicitacao_atividade($pessoa_id, $atividade_id, $funcao_id)
	{
		$this->db->set('funcao_status', '4');
		$this->db->where('Pessoas_Funcoes_Funcoes_funcao_id', $funcao_id);
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa_id);
		$this->db->where('Atividades_atividade_id', $atividade_id);
		$this->db->update('Funcoes_Atividades');
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	//Altera solicitação da atividade para não executado
	public function update_solicitacao_atividade_nao_executado($pessoa_id, $atividade_id, $funcao_id)
	{
		$this->db->set('funcao_status', '3');
		$this->db->where('Pessoas_Funcoes_Funcoes_funcao_id', $funcao_id);
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa_id);
		$this->db->where('Atividades_atividade_id', $atividade_id);
		$this->db->update('Funcoes_Atividades');
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function atividade_finalizacao($pessoa_id, $atividade_id, $funcao_id, $status)
	{
		$this->db->set('funcao_status', $status);
		$this->db->where('Pessoas_Funcoes_Funcoes_funcao_id', $funcao_id);
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa_id);
		$this->db->where('Atividades_atividade_id', $atividade_id);
		$this->db->update('Funcoes_Atividades');
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	//Retorna todas as solicitações pendentes refetes a atividade passada
	public function get_atividade_pendente($id_atividade)
	{
	$this->db->from('funcoes_atividades');
	$this->db->where(array('Atividades_atividade_id' => $id_atividade));
	$this->db->where(array('funcao_status' => '0'));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna os dados da atividade
	public function get_atividade($id_atividade)
	{
	$this->db->from('Atividades');
	$this->db->where(array('atividade_id' => $id_atividade));

	$atividade = $this->db->get();

	    if($atividade->num_rows())
	    {    
	        return $atividade->result_array();
	    }else{
	        return false;
	    }
	}
}