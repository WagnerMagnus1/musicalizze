<?php class Usuarios extends CI_Model
{
	public function get_usuarios()
	{
		$query = $this->db->get('users');

		if($query->num_rows())
		{
			return $query->result_array();
		}else{
			return false;
		}
	}

	public function get_usuario($user_id)
	{
		$this->db->where('user_id', $user_id);

		$usuario = $this->db->get('users');

		if($usuario->num_rows())
		{	
			return $usuario->row_array();
		}else{
			return false;
		}

		if($usuario->num_rows)
		{
			return $usuario->row_array();
		}else{
			return false;
		}
	}

	public function get_usuario_email($user_email)
	{
		$this->db->where('user_email', $user_email);

		$usuario = $this->db->get('users');

		if($usuario->num_rows())
		{	
			return $usuario->row_array();
		}else{
			return false;
		}
	}



	public function check_login($email, $senha)
	{
		$this->db->from('users');
		$this->db->where('user_email', $email);
		$this->db->where('user_password', $senha);
		$usuarios = $this->db->get();

		if($usuarios->num_rows())
		{
			$usuarios = $usuarios->result_array();
			return $usuarios[0];
		}else{
			return false;
		}
	}

	public function cadastrar_usuario($dados_usuario)
	{
		$this->db->insert('users',$dados_usuario);

		return $this->db->affected_rows() ? TRUE : FALSE;
	}
}