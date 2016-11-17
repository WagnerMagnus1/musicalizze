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
//Retorna todas as Atividades em aberto do usuario
	public function get_pessoa_atividade_em_aberto($id_pessoa)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Pessoas_Funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '1'));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '5'));
	$this->db->order_by('Atividades.atividade_data','ASC');
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades em aberto do integrante
	public function get_pessoa_atividade_em_aberto_banda($id_pessoa)
	{
	$this->db->from('Integrantes_Atividades');
	$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_Atividades.Integrantes_integrante_id');
	$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Funcoes', 'Integrantes.Pessoas_Funcoes_Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '1'));
	$this->db->where(array('Integrantes_Atividades.integrante_atividade_status' => '5'));
	$this->db->order_by('Atividades.atividade_data','ASC');
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades em aberto do integrante
	public function get_atividade_aberto_integrante($id_integrante)
	{
	$this->db->from('Integrantes_Atividades');
	$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_Atividades.Integrantes_integrante_id');
	$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Funcoes', 'Integrantes.Pessoas_Funcoes_Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->where(array('Integrantes.integrante_id' => $id_integrante));
	$this->db->where(array('Atividades.atividade_status' => '1'));
	$this->db->where(array('Integrantes_Atividades.integrante_atividade_status' => '5'));
	$this->db->order_by('Atividades.atividade_data','ASC');
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades em aberto do integrante filtrando por id_atividade group_by
	public function get_pessoa_atividade_em_aberto_banda_group_by($id_pessoa)
	{
	$this->db->from('Integrantes_Atividades');
	$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_Atividades.Integrantes_integrante_id');
	$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Funcoes', 'Integrantes.Pessoas_Funcoes_Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '1'));
	$this->db->where(array('Integrantes_Atividades.integrante_atividade_status' => '5'));
	$this->db->group_by('Atividades.atividade_id');
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades em aberto do integrante filtrando por id_atividade group_by
	public function get_pessoa_atividade_em_aberto_banda_no_groupby($id_pessoa)
	{
	$this->db->from('Integrantes_Atividades');
	$this->db->join('Integrantes', 'Integrantes.integrante_id = Integrantes_Atividades.Integrantes_integrante_id');
	$this->db->join('Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Funcoes', 'Integrantes.Pessoas_Funcoes_Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
	$this->db->join('Bandas', 'Integrantes.Bandas_banda_id = Bandas.banda_id');
	$this->db->where(array('Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '1'));
	$this->db->where(array('Integrantes_Atividades.integrante_atividade_status' => '5'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades pendentes do usuario
	public function get_pessoa_atividade_pendente($id_pessoa)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome');
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Pessoas_Funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '1'));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '0'));
	$this->db->order_by('Atividades.atividade_data','ASC');
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades pendentes do usuario COMPLETO
	public function get_pessoa_atividade_pendente_completo($id_pessoa)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Pessoas_Funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '1'));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '0'));
	$this->db->order_by('Atividades.atividade_data','ASC');
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades recusadas do usuario
	public function get_pessoa_atividade_recusado($id_pessoa)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome');
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Pessoas_Funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '1'));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '4'));
	$this->db->order_by('Atividades.atividade_data','ASC');
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
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome');
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '0'));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '3'));
	$this->db->where(array('Funcoes_Atividades.funcao_visualizacao' => '1'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades canceladas
	public function get_pessoa_atividade_cancelado_atividade($id_pessoa, $atividade)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome,Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades_atividade_id' => $atividade));
	$this->db->where(array('Atividades.atividade_status' => '0'));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '3'));
	$this->db->where(array('Funcoes_Atividades.funcao_visualizacao' => '1'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades finalizadas(que ja passaram do prazo), mas que permanece em aberto para o usuario.
	public function get_pessoa_atividade_finalizado_aberto($id_pessoa)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome');
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Pessoas_Funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '2'));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '5'));
	$this->db->order_by('Atividades.atividade_data','ASC');
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades ativas do usuario administrador
	public function get_pessoa_atividade_em_aberto_administrador($id_pessoa)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Pessoas_Funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Atividades.atividade_status' => '1'));
	$this->db->where(array('Funcoes_Atividades.funcao_administrador' => '1'));
	$this->db->order_by('Atividades.atividade_data','ASC');
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Atividades ativas do usuario administrador
	public function get_pessoa_atividade_completo_administrador($id_pessoa)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Pessoas_Funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Funcoes_Atividades.funcao_administrador' => '1'));
	$this->db->order_by('Atividades.atividade_data','ASC');
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Pessoas vinculadas a Atividades
	public function retornar_Pessoas_atividade($id_atividade)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome, funcao_nome, funcao_id, funcao_administrador');
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Atividades.atividade_id' => $id_atividade));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '5'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Pessoas vinculadas a Atividades
	public function retornar_Funcoes_atividade($id_atividade, $id_pessoa)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Integrantes_Atividades', 'Integrantes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->where(array('Atividades.atividade_id' => $id_atividade));
	$this->db->where(array('Pessoas_Funcoes.Pessoas_pessoa_id' => $id_pessoa));

	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Pessoas que receberam notificação para participar da atividade, porém estão como pendente 
	public function get_Pessoas_atividade_pendente($id_atividade)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome, funcao_nome, funcao_id, funcao_administrador');
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Atividades.atividade_id' => $id_atividade));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '0'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as Pessoas vinculadas a Atividades com resposta para o ADM verificar
	public function get_pessoa_atividade_aceitas_recusadas($id_atividade)
	{
	$this->db->select('atividade_id,atividade_titulo,pessoa_id,pessoa_nome,funcao_status');
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Atividades.atividade_id' => $id_atividade));
	$this->db->where(array('Funcoes_Atividades.funcao_visualizacao' => '1'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna a atividade vincula a pessoa administrador
	public function get_pessoa_atividade_administrador($id_pessoa, $id_atividade)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->where(array('Pessoas_Funcoes.Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('atividade_id' => $id_atividade));
	$this->db->where(array('funcao_administrador' => '1'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna o administrador da atividade
	public function get_administrador_atividade($id_atividade)
	{
	$this->db->select('atividade_id,pessoa_id,pessoa_nome,pessoa_foto');
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('atividade_id' => $id_atividade));
	$this->db->where(array('funcao_administrador' => '1'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os dados do administrador da atividade e da atividade
	public function get_administrador_atividade_completo($id_atividade)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('atividade_id' => $id_atividade));
	$this->db->where(array('funcao_administrador' => '1'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os dados da pessoa e da atividade
	public function get_pessoa_atividade_completo($id_atividade, $id_pessoa)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('atividade_id' => $id_atividade));
	$this->db->where(array('pessoa_id' => $id_pessoa));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os dados da pessoa e da atividade STATUS 
	public function get_pessoa_atividade_completo_status($id_atividade, $id_pessoa, $status)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('atividade_id' => $id_atividade));
	$this->db->where(array('pessoa_id' => $id_pessoa));
	$this->db->where(array('funcao_status' => $status));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todos os dados da pessoa e da atividade
	public function get_pessoa_atividade_completo_visualizacao($id_atividade, $id_pessoa, $visualizacao)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->join('Pessoas', 'Pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('atividade_id' => $id_atividade));
	$this->db->where(array('pessoa_id' => $id_pessoa));
	$this->db->where(array('funcao_visualizacao' => $visualizacao));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna as Atividades em aberto de uma determinada função da pessoa
	public function get_atividade_aberto_funcao_pessoa($id_pessoa, $id_funcao)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->join('Atividades', 'Funcoes_Atividades.Atividades_atividade_id = Atividades.atividade_id');
	$this->db->join('Pessoas_Funcoes', 'Pessoas_Funcoes.Pessoas_pessoa_id = Funcoes_Atividades.Pessoas_Funcoes_Pessoas_pessoa_id and Pessoas_Funcoes.Funcoes_funcao_id = Funcoes_Atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('Funcoes', 'Pessoas_Funcoes.Funcoes_funcao_id = Funcoes.funcao_id');
	$this->db->where(array('Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Pessoas_Funcoes_Funcoes_funcao_id' => $id_funcao));
	$this->db->where(array('atividade_status' => '1'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
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
		$this->db->set('funcao_visualizacao', 1);
		$this->db->where('Pessoas_Funcoes_Funcoes_funcao_id', $funcao_id);
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa_id);
		$this->db->where('Atividades_atividade_id', $atividade_id);
		$this->db->update('Funcoes_Atividades');
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function atividade_finalizacao($pessoa_id, $atividade_id, $funcao_id, $status, $valor)
	{
		$this->db->set('funcao_status', $status);
		$this->db->set('funcao_valor', $valor);
		$this->db->where('Pessoas_Funcoes_Funcoes_funcao_id', $funcao_id);
		$this->db->where('Pessoas_Funcoes_Pessoas_pessoa_id', $pessoa_id);
		$this->db->where('Atividades_atividade_id', $atividade_id);
		$this->db->update('Funcoes_Atividades');
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	//Retorna todas as solicitações pendentes refetes a atividade passada
	public function get_atividade_pendente($id_atividade)
	{
	$this->db->from('Funcoes_Atividades');
	$this->db->where(array('Atividades_atividade_id' => $id_atividade));
	$this->db->where(array('funcao_status' => '0'));
	$Atividades = $this->db->get();

	    if($Atividades->num_rows())
	    {    
	        return $Atividades->result_array();
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