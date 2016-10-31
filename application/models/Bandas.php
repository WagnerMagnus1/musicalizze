<?php class Bandas extends CI_Model
{
	public function cadastrar($dados_banda)
	{
		$this->db->insert('bandas',$dados_banda);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function inserir_genero($dados)
	{
		$this->db->insert('Bandas_Generos',$dados);
		$insert_id = $this->db->insert_id();
		return $this->db->affected_rows() ? TRUE : FALSE;
	}

	public function deletar_banda($banda_id)
	{
		$this->db->where('banda_id', $banda_id);
		$this->db->delete('Bandas');
	}

	public function deletar_genero_banda($banda_id)
	{
		$this->db->where('Bandas_banda_id', $banda_id);
		$this->db->delete('Bandas_Generos');
	}

	//Busca Completa sobre os dados da banda
	public function get_banda($banda_id)
	{
		$this->db->from('Bandas');
		$this->db->where(array('Bandas.banda_id' => $banda_id));

		$banda = $this->db->get();

		if($banda->num_rows())
		{	
			return $banda->result_array();
		}else{
			return false;
		}
	}

	//Busca Completa sobre os generos ATIVOS da banda
	public function get_genero_banda_ativo($banda_id)
	{
		$this->db->from('Bandas');
		$this->db->join('Bandas_Generos', 'Bandas_Generos.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Generos', 'Generos.genero_id = Bandas_Generos.Generos_genero_id');
		$this->db->where(array('Bandas.banda_id' => $banda_id));
		$this->db->where(array('Bandas_Generos.disponibilidade' => '1'));

		$banda = $this->db->get();

		if($banda->num_rows())
		{	
			return $banda->result_array();
		}else{
			return false;
		}
	}

	//Busca Completa sobre os generos INATIVOS da banda
	public function get_genero_banda_inativo($banda_id)
	{
		$this->db->from('Bandas');
		$this->db->join('Bandas_Generos', 'Bandas_Generos.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Generos', 'Generos.genero_id = Bandas_Generos.Generos_genero_id');
		$this->db->where(array('Bandas.banda_id' => $banda_id));
		$this->db->where(array('Bandas_Generos.disponibilidade' => '0'));

		$banda = $this->db->get();

		if($banda->num_rows())
		{	
			return $banda->result_array();
		}else{
			return false;
		}
	}
	//Retorna todos os generos da banda 
	public function get_banda_generos($banda_id)
	{
		$this->db->from('Bandas_Generos');
		$this->db->join('Generos', 'Bandas_Generos.Generos_genero_id = Generos.genero_id');
		$this->db->where(array('Bandas_banda_id' => $banda_id));
		$funcao = $this->db->get();

		if($funcao->num_rows())
		{	
			return $funcao->result_array();
		}else{
			return false;
		}
	}
	//Editar dados da banda
	public function update($dados_banda)
	{
		$this->db->where('banda_id', $dados_banda['banda_id']);
		$this->db->update('Bandas', $dados_banda);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}
	//Editar disponibilidade de genero da banda
	public function update_genero_disponibilidade($dados)
	{
		$this->db->where('Bandas_banda_id', $dados['Bandas_banda_id']);
		$this->db->where('Generos_genero_id', $dados['Generos_genero_id']);
		$this->db->update('Bandas_Generos', $dados);
		return $this->db->affected_rows() ? TRUE : FALSE;
	}
	//Busca por nome das bandas
	public function get_nome_banda_parecido($nome)
	{
		$this->db->select('banda_id,banda_nome,banda_estado,banda_foto');
		$this->db->from('Bandas');
		$this->db->or_like(array('banda_nome' => $nome));
		$nomes = $this->db->get();

		if($nomes->num_rows())
		{	
			return $nomes->result_array();
		}else{
			return false;
		}
	}
	//Busca todas as bandas de um determinado genero (ativa), porém é detalhado para mostrar no MAPA
	public function get_localizacao_genero_ativo_banda($genero)
	{
		$this->db->select('banda_id,banda_nome,pessoa_latitude,pessoa_longitude,genero_nome,banda_foto');
		$this->db->from('bandas');
		$this->db->join('Bandas_Generos', 'Bandas_Generos.Bandas_banda_id = Bandas.banda_id');
		$this->db->join('Generos', 'Generos.genero_id = Bandas_Generos.Generos_genero_id');
		$this->db->join('Integrantes', 'Integrantes.bandas_banda_id = Bandas.banda_id');
		$this->db->join('Pessoas', 'Pessoas.pessoa_id = Integrantes.Pessoas_Funcoes_Pessoas_pessoa_id');
		$this->db->where(array('integrante_administrador' => '1'));
		$this->db->where(array('disponibilidade' => '1'));
		$this->db->where(array('genero_id' => $genero));
		$genero = $this->db->get();

		if($genero->num_rows())
		{	
			return $genero->result_array();
		}else{
			return false;
		}
	}
}