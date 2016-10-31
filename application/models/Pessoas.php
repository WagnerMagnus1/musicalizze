<?php class Pessoas extends CI_Model
{
	public function get_usuario_pessoa($id_usuario)
	{
		$this->db->from('pessoas');
		$this->db->where('Users_user_id', $id_usuario);

		$pessoa = $this->db->get();

		if($pessoa->num_rows())
		{	
			return $pessoa->row_array();
		}else{
			return false;
		}
	}

	public function get_face_pessoa($id_usuario)
	{
		$this->db->from('pessoas');
		$this->db->where('Users_Facebook_facebook_id', $id_usuario);

		$pessoa = $this->db->get();

		if($pessoa->num_rows())
		{	
			return $pessoa->row_array();
		}else{
			return false;
		}
	}

	public function get_pessoa($id_pessoa)
	{
		$this->db->from('pessoas');
		$this->db->where('pessoa_id', $id_pessoa);

		$pessoa = $this->db->get();

		if($pessoa->num_rows())
		{	
			return $pessoa->row_array();
		}else{
			return false;
		}
	}

	public function cadastrar_pessoa($dados_usuario)
	{
		$this->db->insert('Pessoas',$dados_usuario);

		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function vincular_funcao($dados_usuario)
	{
		$this->db->insert('Pessoas_funcoes',$dados_usuario);

		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function get_pessoa_funcao($id_pessoa)
	{
		$this->db->from('pessoas_funcoes');
		$this->db->where('Pessoas_pessoa_id', $id_pessoa);

		$pessoa = $this->db->get();

		if($pessoa->num_rows())
		{	
			return $pessoa->result_array();
		}else{
			return false;
		}
	}

	public function get_pessoas_funcoes($id_pessoa)
	{
		$this->db->from('Pessoas_Funcoes');
		$this->db->join('funcoes', 'Pessoas_Funcoes.funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('Pessoas_pessoa_id' => $id_pessoa));
		$funcao = $this->db->get();

		if($funcao->num_rows())
		{	
			return $funcao->result_array();
		}else{
			return false;
		}
	}
	public function get_pessoas_funcoes_ativo($id_pessoa)
	{
		$this->db->from('Pessoas_Funcoes');
		$this->db->join('funcoes', 'Pessoas_Funcoes.funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('Pessoas_pessoa_id' => $id_pessoa));
		$this->db->where(array('disponibilidade' => '1'));
		$funcao = $this->db->get();

		if($funcao->num_rows())
		{	
			return $funcao->result_array();
		}else{
			return false;
		}
	}
	public function get_pessoas_funcoes_inativo($id_pessoa)
	{
		$this->db->from('Pessoas_Funcoes');
		$this->db->join('funcoes', 'Pessoas_Funcoes.funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('Pessoas_pessoa_id' => $id_pessoa));
		$this->db->where(array('disponibilidade' => '0'));
		$funcao = $this->db->get();

		if($funcao->num_rows())
		{	
			return $funcao->result_array();
		}else{
			return false;
		}
	}

	public function delete_pessoa($id_pessoa)
	{
		$this->db->where('pessoa_id', $id_pessoa);
		$this->db->delete('pessoas');
	}

	public function update($dados_usuario)
	{
		$this->db->where('pessoa_id', $dados_usuario['pessoa_id']);
		$this->db->update('pessoas', $dados_usuario);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function update_disponibilidade_funcao($dados_funcao_pessoa)
	{
		$this->db->where('Pessoas_pessoa_id', $dados_funcao_pessoa['Pessoas_pessoa_id']);
		$this->db->where('Funcoes_funcao_id', $dados_funcao_pessoa['Funcoes_funcao_id']);
		$this->db->update('Pessoas_Funcoes', $dados_funcao_pessoa);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function get_pessoas_funcoes_wherefuncoes($id_pessoa, $id_funcao)
	{
		$this->db->from('Pessoas_Funcoes');
		$this->db->join('funcoes', 'Pessoas_Funcoes.funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('Pessoas_pessoa_id' => $id_pessoa));
		$this->db->where(array('Funcoes_funcao_id' => $id_funcao));
		$funcao = $this->db->get();

		if($funcao->num_rows())
		{	
			return $funcao->result_array();
		}else{
			return false;
		}
	}
	//Busca por nome parecidos ao digitado (BUSCA PESSOA)
	public function get_nome_pessoa_parecido($nome)
	{
		$this->db->select('pessoa_id,pessoa_nome,pessoa_sobrenome,pessoa_estado,pessoa_foto');
		$this->db->from('Pessoas');
		$this->db->or_like(array('pessoa_nome' => $nome, 'pessoa_sobrenome' => $nome));
		$nomes = $this->db->get();

		if($nomes->num_rows())
		{	
			return $nomes->result_array();
		}else{
			return false;
		}
	}
	//Busca por sobrenome parecidos ao digitado (BUSCA PESSOA)
	public function get_sobrenome_pessoa_parecido($nome)
	{
		$this->db->select('pessoa_id,pessoa_nome,pessoa_sobrenome,pessoa_estado,pessoa_foto');
		$this->db->from('Pessoas');
		$this->db->or_like(array('pessoa_sobrenome' => $nome));
		$nomes = $this->db->get();

		if($nomes->num_rows())
		{	
			return $nomes->result_array();
		}else{
			return false;
		}
	}

	//Busca todas as pessoas de uma determinada função (ativa), porém é detalhado para mostrar no MAPA
	public function get_localizacao_funcao_ativo_pessoas($funcao)
	{
		$this->db->select('pessoa_id,pessoa_nome,pessoa_latitude,pessoa_longitude,funcao_nome,pessoa_foto,pessoa_sobrenome');
		$this->db->from('pessoas');
		$this->db->join('pessoas_funcoes', 'Pessoas_Funcoes.pessoas_pessoa_id = pessoas.pessoa_id');
		$this->db->join('funcoes', 'Pessoas_Funcoes.funcoes_funcao_id = Funcoes.funcao_id');
		$this->db->where(array('disponibilidade' => '1'));
		$this->db->where(array('funcao_id' => $funcao));
		$funcao = $this->db->get();

		if($funcao->num_rows())
		{	
			return $funcao->result_array();
		}else{
			return false;
		}
	}

	//Retorna todas as atividades executadas pelo usuario
	public function get_usuario_atividades_status($id_pessoa, $data_inicio, $data_final, $status)
	{
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->where(array('atividade_status' => '2'));
	$this->db->where(array('funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => $status));
	$this->db->where(array('atividades.atividade_data >=' => $data_inicio));
	$this->db->where(array('atividades.atividade_data <=' => $data_final));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as atividades executadas com sucesso como ADM
	public function get_usuario_atividades_adm($id_pessoa, $data_inicio, $data_final)
	{
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->where(array('atividade_status' => '2'));
	$this->db->where(array('funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '2'));
	$this->db->where(array('Funcoes_Atividades.funcao_administrador' => '1'));
	$this->db->where(array('atividades.atividade_data >=' => $data_inicio));
	$this->db->where(array('atividades.atividade_data <=' => $data_final));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}

	//Retorna todas as atividades que a pessoa foi convidado
	public function get_usuario_atividades_convidado($id_pessoa, $data_inicio, $data_final)
	{
	$this->db->from('funcoes_atividades');
	$this->db->join('atividades', 'funcoes_atividades.Atividades_atividade_id = atividades.atividade_id');
	$this->db->where(array('atividade_status' => '2'));
	$this->db->where(array('funcoes_atividades.Pessoas_Funcoes_Pessoas_pessoa_id' => $id_pessoa));
	$this->db->where(array('Funcoes_Atividades.funcao_status' => '2'));
	$this->db->where(array('atividades.atividade_data >=' => $data_inicio));
	$this->db->where(array('atividades.atividade_data <=' => $data_final));
	$atividades = $this->db->get();

	    if($atividades->num_rows())
	    {    
	        return $atividades->result_array();
	    }else{
	        return false;
	    }
	}
}