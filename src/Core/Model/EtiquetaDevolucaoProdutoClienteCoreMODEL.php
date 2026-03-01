<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 20/06/2020 15:56:09
 */ 

namespace Store\Core\Model;

class EtiquetaDevolucaoProdutoClienteCoreMODEL 
{

	public $id = "id";
	public $plp = "plp";
	public $codigo_rastreio = "codigo_rastreio";
	public $data_validade = "data_validade";
	public $data_emissao = "data_emissao";
	public $status = "status";
	public $id_pedido = "id_pedido";
	public $id_destinatario = "id_destinatario";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getPlp(){

		return $this->plp;
	}

	public function setPlp($plp){

		$this->plp = $plp;
		return $this->plp;
	}

	public function getCodigoRastreio(){

		return $this->codigo_rastreio;
	}

	public function setCodigoRastreio($codigo_rastreio){

		$this->codigo_rastreio = $codigo_rastreio;
		return $this->codigo_rastreio;
	}

	public function getDataValidade(){

		return $this->data_validade;
	}

	public function setDataValidade($data_validade){

		$this->data_validade = $data_validade;
		return $this->data_validade;
	}

	public function getDataEmissao(){

		return $this->data_emissao;
	}

	public function setDataEmissao($data_emissao){

		$this->data_emissao = $data_emissao;
		return $this->data_emissao;
	}

	public function getStatus(){

		return $this->status;
	}

	public function setStatus($status){

		$this->status = $status;
		return $this->status;
	}

	public function getIdPedido(){

		return $this->id_pedido;
	}

	public function setIdPedido($id_pedido){

		$this->id_pedido = $id_pedido;
		return $this->id_pedido;
	}

	public function getIdDestinatario(){

		return $this->id_destinatario;
	}

	public function setIdDestinatario($id_destinatario){

		$this->id_destinatario = $id_destinatario;
		return $this->id_destinatario;
	}

}