<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 14/07/2020 16:45:03
 */ 

namespace Store\Core\Model;

class EnderecoLocalizacaoClienteCoreMODEL 
{

	public $id = "id";
	public $endereco = "endereco";
	public $bairro = "bairro";
	public $numero = "numero";
	public $cidade = "cidade";
	public $uf = "uf";
	public $cep = "cep";
	public $ip = "ip";
	public $id_cliente = "id_cliente";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getEndereco(){

		return $this->endereco;
	}

	public function setEndereco($endereco){

		$this->endereco = $endereco;
		return $this->endereco;
	}

	public function getBairro(){

		return $this->bairro;
	}

	public function setBairro($bairro){

		$this->bairro = $bairro;
		return $this->bairro;
	}

	public function getNumero(){

		return $this->numero;
	}

	public function setNumero($numero){

		$this->numero = $numero;
		return $this->numero;
	}

	public function getCidade(){

		return $this->cidade;
	}

	public function setCidade($cidade){

		$this->cidade = $cidade;
		return $this->cidade;
	}

	public function getUf(){

		return $this->uf;
	}

	public function setUf($uf){

		$this->uf = $uf;
		return $this->uf;
	}

	public function getCep(){

		return $this->cep;
	}

	public function setCep($cep){

		$this->cep = $cep;
		return $this->cep;
	}

	public function getIp(){

		return $this->ip;
	}

	public function setIp($ip){

		$this->ip = $ip;
		return $this->ip;
	}

	public function getIdCliente(){

		return $this->id_cliente;
	}

	public function setIdCliente($id_cliente){

		$this->id_cliente = $id_cliente;
		return $this->id_cliente;
	}

}