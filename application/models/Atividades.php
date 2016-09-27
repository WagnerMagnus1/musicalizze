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
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->join('pessoas_funcoes', 'pessoas_funcoes.Pessoas_pessoa_id = funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id and pessoas_funcoes.Funcoes_funcao_id = funcoes_atividades.Pessoas_Funcoes_Funcoes_funcao_id');
	$this->db->join('funcoes', 'pessoas_funcoes.Funcoes_funcao_id = funcoes.funcao_id');
	$this->db->join('Pessoas', 'pessoas.pessoa_id = Pessoas_Funcoes.Pessoas_pessoa_id');
	$this->db->where(array('Atividades.atividade_id' => $id_atividade));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna se a atividade esta vinculada a alguma banda
	public function retornar_atividade_banda($id_atividade)
	{
	$this->db->from('Atividades_integrantes');
	$this->db->where(array('Funcoes_Atividades_Atividades_atividade_id' => $id_atividade));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna a atividade vincula a pessoa
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

	public function update($dados_atividade)
	{
		$this->db->where('atividade_id', $dados_atividade['atividade_id']);
		$this->db->update('Atividades', $dados_atividade);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

}