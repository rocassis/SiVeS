<?php

class Vendas extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        if((!$this->session->userdata('session_id')) || (!$this->session->userdata('logado'))) {
            redirect('sistema/login');
        }
		
		$this->load->helper(array('form','codegen_helper'));
		$this->load->model('vendas_model','',TRUE);
		$this->load->model('produtos_model','',TRUE);
		$this->data['menuVendas'] = 'Vendas';
	}	
	
	function index(){
		$this->gerenciar();
	}

	function gerenciar(){
        
        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'vVenda')){
           $this->session->set_flashdata('error','Você não tem permissão para visualizar vendas.');
           redirect(base_url());
        }

        $this->load->library('pagination');
        
        
        $config['base_url'] = base_url().'index.php/vendas/gerenciar/';
        $config['total_rows'] = $this->vendas_model->count('os');
        $config['per_page'] = 10;
        $config['next_link'] = 'Próxima';
        $config['prev_link'] = 'Anterior';
        $config['full_tag_open'] = '<div class="pagination alternate"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><a style="color: #2D335B"><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = 'Primeira';
        $config['last_link'] = 'Última';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        	
        $this->pagination->initialize($config); 	

		$this->data['results'] = $this->vendas_model->get('vendas','*','',$config['per_page'],$this->uri->segment(3));
       
	    $this->data['view'] = 'vendas/vendas';
       	$this->load->view('tema/topo',$this->data);
      
		
    }
	
    function adicionar(){

        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'aVenda')){
          $this->session->set_flashdata('error','Você não tem permissão para adicionar Vendas.');
          redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        
        if ($this->form_validation->run('vendas') == false) {
           $this->data['custom_error'] = (validation_errors() ? true : false);
        } else {

            $dataVenda = $this->input->post('dataVenda');

            try {
                
                $dataVenda = explode('/', $dataVenda);
                $dataVenda = $dataVenda[2].'-'.$dataVenda[1].'-'.$dataVenda[0];


            } catch (Exception $e) {
               $dataVenda = date('Y/m/d'); 
            }

            $data = array(
                'dataVenda' => $dataVenda,
                'clientes_id' => $this->input->post('clientes_id'),
                'usuarios_id' => $this->input->post('usuarios_id'),
                'faturado' => 0
            );

            if (is_numeric($id = $this->vendas_model->add('vendas', $data, true)) ) {
                $this->session->set_flashdata('success','Venda iniciada com sucesso, adicione os produtos.');
                redirect('vendas/editar/'.$id);

            } else {
                
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }
         
        $this->data['view'] = 'vendas/adicionarVenda';
        $this->load->view('tema/topo', $this->data);
    }
    

    
    function editar() {

        if(!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))){
            $this->session->set_flashdata('error','Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('sistema');
        }

        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'eVenda')){
          $this->session->set_flashdata('error','Você não tem permissão para editar vendas');
          redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        if ($this->form_validation->run('vendas') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {

            $dataVenda = $this->input->post('dataVenda');

            try {
                
                $dataVenda = explode('/', $dataVenda);
                $dataVenda = $dataVenda[2].'-'.$dataVenda[1].'-'.$dataVenda[0];


            } catch (Exception $e) {
               $dataVenda = date('Y/m/d'); 
            }

            $data = array(
                'dataVenda' => $dataVenda,
                'usuarios_id' => $this->input->post('usuarios_id'),
                'clientes_id' => $this->input->post('clientes_id')
            );

            if ($this->vendas_model->edit('vendas', $data, 'idVendas', $this->input->post('idVendas')) == TRUE) {
                $this->session->set_flashdata('success','Venda editada com sucesso!');
                redirect(base_url() . 'index.php/vendas/editar/'.$this->input->post('idVendas'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro</p></div>';
            }
        }

        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['view'] = 'vendas/editarVenda';
        $this->load->view('tema/topo', $this->data);
   
    }

    public function visualizar(){

        if(!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))){
            $this->session->set_flashdata('error','Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('sistema');
        }
        
        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'vVenda')){
          $this->session->set_flashdata('error','Você não tem permissão para visualizar vendas.');
          redirect(base_url());
        }

        $this->data['custom_error'] = '';
        $this->load->model('sistema_model');
        $this->data['result'] = $this->vendas_model->getById($this->uri->segment(3));
        $this->data['produtos'] = $this->vendas_model->getProdutos($this->uri->segment(3));
        $this->data['emitente'] = $this->sistema_model->getEmitente();
        
        $this->data['view'] = 'vendas/visualizarVenda';
        $this->load->view('tema/topo', $this->data);
       
    }
	
    function excluir(){

        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'dVenda')){
          $this->session->set_flashdata('error','Você não tem permissão para excluir vendas');
          redirect(base_url());
        }
        
        $id =  $this->input->post('id');
        if ($id == null){

            $this->session->set_flashdata('error','Erro ao tentar excluir venda.');            
            redirect(base_url().'index.php/vendas/gerenciar/');
        }

        $this->db->where('vendas_id', $id);
        $this->db->delete('itens_de_vendas');
		
		// $this->db->where('vendas_id', $id);
        //$this->db->delete('itens_baixa');
		//elimina itens de baixa junto com a venda

        $this->db->where('idVendas', $id);
        $this->db->delete('vendas');           

        $this->session->set_flashdata('success','Venda excluída com sucesso!');            
        redirect(base_url().'index.php/vendas/gerenciar/');

    }
    
    function removeProduto(){

         if(!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))){
            $this->session->set_flashdata('error','Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('sistema');
        }
        
        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'dVenda')){
          $this->session->set_flashdata('error','Você não tem permissão para excluir Produto');
          redirect(base_url());
        }
       
        $this->db->where('idItens', $this->uri->segment(3));
        $this->db->delete('itens_de_vendas');
		
		//$this->baixaQtdeProduto($this->uri->segment(5),$this->uri->segment(4),$this->uri->segment(6));
		//remove qtde de produto da tabela de baixa
		
        $this->session->set_flashdata('success','Venda excluída com sucesso!');            
        redirect(base_url().'index.php/vendas/editar/'.$this->uri->segment(4));

    }

    public function autoCompleteProduto(){
        
        if (isset($_GET['term'])){
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteProduto($q);
        }

    }

    public function autoCompleteCliente(){

        if (isset($_GET['term'])){
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteCliente($q);
        }

    }

    public function autoCompleteUsuario(){

        if (isset($_GET['term'])){
            $q = strtolower($_GET['term']);
            $this->vendas_model->autoCompleteUsuario($q);
        }

    }



    public function adicionarProduto(){

        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'eVenda')){
          $this->session->set_flashdata('error','Você não tem permissão para editar vendas.');
          redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'trim|required|xss_clean');
        $this->form_validation->set_rules('idProduto', 'Produto', 'trim|required|xss_clean');
        $this->form_validation->set_rules('idVendasProduto', 'Vendas', 'trim|required|xss_clean');
        
        if($this->form_validation->run() == false){
           echo json_encode(array('result'=> false)); 
        }
        else{

            $preco = $this->input->post('preco');
            $quantidade = $this->input->post('quantidade');
            $subtotal = $preco * $quantidade;
            $produto = $this->input->post('idProduto');
            $data = array(
                'quantidade'=> $quantidade,
                'subTotal'=> $subtotal,
                'produtos_id'=> $produto,
                'vendas_id'=> $this->input->post('idVendasProduto'),
            );

            if($this->vendas_model->add('itens_de_vendas', $data) == true){
                //$this->baixaProduto($quantidade,$produto,$this->input->post('idVendasProduto'));
                //adiciona novo produto na tabela de baixa
                echo json_encode(array('result'=> true));
                
            }else{
                echo json_encode(array('result'=> false));
            }

        }
        
      
    }
    
    public function addMaisProduto(){//Função para adicionar quantidade de produto pelo botão 
	
		if(!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))){
            $this->session->set_flashdata('error','Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('sistema');
        }
        
        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'eVenda')){
          $this->session->set_flashdata('error','Você não tem permissão para editar vendas.');
          redirect(base_url());
        }

		$quantidade = $this->uri->segment(6) + 1;
		$subtotal = $this->uri->segment(5) + $this->uri->segment(4);
		
		$data = array(
			'quantidade'=> $quantidade,
			'subTotal'=> $subtotal );

		if($this->vendas_model->edit('itens_de_vendas', $data, 'idItens', $this->uri->segment(3)) == true){
			echo json_encode(array('result'=> true));
			redirect(base_url() . 'index.php/vendas/editar/'.$this->uri->segment(7));
			
		}else{
			$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured</p></div>';
		}
    }
    
    public function addMenosProduto(){//Função para diminuir quantidade de produto pelo botão 
	
		if(!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))){
            $this->session->set_flashdata('error','Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('sistema');
        }
        
        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'eVenda')){
          $this->session->set_flashdata('error','Você não tem permissão para editar vendas.');
          redirect(base_url());
        }

		$preco = $this->input->post('preco');
		$quantidade = $this->uri->segment(6) - 1;
		$subtotal = $this->uri->segment(5) - $this->uri->segment(4);
		
		$data = array(
			'quantidade'=> $quantidade,
			'subTotal'=> $subtotal );

		if($this->vendas_model->edit('itens_de_vendas', $data, 'idItens', $this->uri->segment(3)) == true){
			echo json_encode(array('result'=> true));
			redirect(base_url() . 'index.php/vendas/editar/'.$this->uri->segment(7));
			
		}else{
			$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured</p></div>';
		}
    }

    function excluirProduto(){

            if(!$this->permission->checkPermission($this->session->userdata('permissao'),'eVenda')){
              $this->session->set_flashdata('error','Você não tem permissão para editar Vendas');
              redirect(base_url());
            }

            $ID = $this->input->post('idProduto');
            if($this->vendas_model->delete('itens_de_vendas','idItens',$ID) == true){

                $produto = $this->input->post('produto');
                echo json_encode(array('result'=> true));
            }
            else{
                echo json_encode(array('result'=> false));
            }           
    }


    public function faturar() {

        if(!$this->permission->checkPermission($this->session->userdata('permissao'),'eVenda')){
              $this->session->set_flashdata('error','Você não tem permissão para editar Vendas');
              redirect(base_url());
            }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
 

        if ($this->form_validation->run('receita') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {


            $vencimento = $this->input->post('vencimento');
            $recebimento = $this->input->post('recebimento');

            try {
                
                $vencimento = explode('/', $vencimento);
                $vencimento = $vencimento[2].'-'.$vencimento[1].'-'.$vencimento[0];

                if($recebimento != null){
                    $recebimento = explode('/', $recebimento);
                    $recebimento = $recebimento[2].'-'.$recebimento[1].'-'.$recebimento[0];

                }
            } catch (Exception $e) {
               $vencimento = date('Y/m/d'); 
            }

            $data = array(
                'descricao' => set_value('descricao'),
                'valor' => $this->input->post('valor'),
                'clientes_id' => $this->input->post('clientes_id'),
                'data_vencimento' => $vencimento,
                'data_pagamento' => $recebimento,
                'baixado' => $this->input->post('recebido'),
                'cliente_fornecedor' => set_value('cliente'),
                'forma_pgto' => $this->input->post('formaPgto'),
                'tipo' => $this->input->post('tipo')
            );

            if ($this->vendas_model->add('lancamentos',$data) == TRUE) {
                
                $venda = $this->input->post('vendas_id');

                $this->db->set('faturado',1);
                $this->db->set('valorTotal',$this->input->post('valor'));
                $this->db->where('idVendas', $venda);
                $this->db->update('vendas');

                $this->session->set_flashdata('success','Venda faturada com sucesso!');
                $json = array('result'=>  true);
                echo json_encode($json);
                $this->darBaixaProduto($this->input->post('vendas_id')); //Da baixa no estoque
                die();                
               
            } else {
                $this->session->set_flashdata('error','Ocorreu um erro ao tentar faturar venda.');
                $json = array('result'=>  false);
                echo json_encode($json);
                die();
            }
        }

        $this->session->set_flashdata('error','Ocorreu um erro ao tentar faturar venda.');
        $json = array('result'=>  false);
        echo json_encode($json);
        
    }

	function baixaProduto($qtde,$produtoId,$vendaId)
	{
		$result = $this->produtos_model->getBaixaById($vendaId,$produtoId);
				
		if(!$result)
		{
			$data = array(
                'quantidade'=> $qtde,
                'produtos_id'=> $produtoId,
                'vendas_id'=> $vendaId,
                'baixa'=> 0
                
            );

            if($this->produtos_model->add('itens_baixa', $data) == true){
            }else{
                echo json_encode(array('result'=> false));
            }
		}
		else
		{
			$baixa = $result->quantidade;
			$baixa = $baixa + $qtde;
			$data = array('quantidade'=> $baixa);
		
			if ($this->produtos_model->edit('itens_baixa', $data, 'idBaixa', $result->idBaixa) == TRUE) {
				
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured</p></div>';
            }
		}
	}
	
	function baixaQtdeProduto($qtde,$produtoId,$vendaId)
	{
		$result = $this->produtos_model->getBaixaById($vendaId,$produtoId);
				
		$baixa = $result->quantidade;
		$baixa = $baixa - $qtde;
		$data = array('quantidade'=> $baixa);
	
		if ($this->produtos_model->edit('itens_baixa', $data, 'idBaixa', $result->idBaixa) == TRUE) {
			
		} else {
			$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured</p></div>';
		}
	
	}
	
	function darBaixaProduto($idVenda)
	{
		$result = $this->vendas_model->qtdeProd($idVenda);
		
		
		 foreach ($result as $r)
		 {	
			$estoque = $this->produtos_model->getById($r->produtos_id);
			$baixa = number_format($estoque->estoque - $r->qtde);
			$data = array('estoque' => $baixa);
			
            if ($this->produtos_model->edit('produtos', $data, 'idProdutos', $r->produtos_id) == TRUE) {
                
				
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured</p></div>';
            }
         }

	}
	
}

