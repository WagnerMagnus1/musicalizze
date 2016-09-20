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


}