<?php
class Produtos_model extends CI_Model {

    /**
     * author: Ramon Silva 
     * email: silva018-mg@yahoo.com.br
     * 
     */
    
    function __construct() {
        parent::__construct();
    }

    
    function get($table,$fields,$where='',$perpage=0,$start=0,$one=false,$array='array'){
        
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('idProdutos','desc');
        $this->db->limit($perpage,$start);
        if($where){
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }
    
    function getProd($table,$fields){
        
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('idProdutos','desc');
        
        return $this->db->get()->result();
    }
    
    function getProdBaixa()
    {
		$query = "SELECT produtos_id, SUM(quantidade) as qtde FROM itens_de_vendas, vendas 
		WHERE vendas_id = idVendas AND faturado = 0 GROUP BY produtos_id";
        return $this->db->query($query)->result();
	}

    function getById($id){
        $this->db->where('idProdutos',$id);
        $this->db->limit(1);
        return $this->db->get('produtos')->row();
    }
    
    function getBaixaById($vendaId,$produtoId){
        $query = "SELECT idBaixa, quantidade FROM itens_baixa WHERE vendas_id = ? AND produtos_id = ? LIMIT 1";
        return $this->db->query($query, array($vendaId,$produtoId))->row();
    }
    
    function add($table,$data){
        $this->db->insert($table, $data);         
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
    }
    
    function edit($table,$data,$fieldID,$ID){
        $this->db->where($fieldID,$ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }
    
    function delete($table,$fieldID,$ID){
        $this->db->where($fieldID,$ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;        
    }   
	
	function count($table){
		return $this->db->count_all($table);
	}
}
