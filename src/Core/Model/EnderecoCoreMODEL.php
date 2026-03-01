<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class EnderecoCoreMODEL 
{

	public $id = "id";
	public $destinatario = "destinatario";
	public $endereco = "endereco";
	public $bairro = "bairro";
	public $cidade = "cidade";
	public $uf = "uf";
	public $cep = "cep";
	public $pais = "pais";
	public $numero = "numero";
	public $principal = "principal";
	public $id_cliente = "id_cliente";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getDestinatario(){

		return $this->destinatario;
	}

	public function setDestinatario($destinatario){

		$this->destinatario = $destinatario;
		return $this->destinatario;
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

	public function getPais(){

		return $this->pais;
	}

	public function setPais($pais){

		$this->pais = $pais;
		return $this->pais;
	}

	public function getNumero(){

		return $this->numero;
	}

	public function setNumero($numero){

		$this->numero = $numero;
		return $this->numero;
	}

	public function getPrincipal(){

		return $this->principal;
	}

	public function setPrincipal($principal){

		$this->principal = $principal;
		return $this->principal;
	}

	public function getIdCliente(){

		return $this->id_cliente;
	}

	public function setIdCliente($id_cliente){

		$this->id_cliente = $id_cliente;
		return $this->id_cliente;
	}

}