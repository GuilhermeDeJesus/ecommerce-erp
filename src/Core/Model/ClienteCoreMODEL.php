<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class ClienteCoreMODEL 
{

	public $id = "id";
	public $nome = "nome";
	public $email = "email";
	public $senha = "senha";
	public $sexo = "sexo";
	public $data_nascimento = "data_nascimento";
	public $cpf = "cpf";
	public $telefone = "telefone";
	public $ativo = "ativo";
	public $date_create = "date_create";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getNome(){

		return $this->nome;
	}

	public function setNome($nome){

		$this->nome = $nome;
		return $this->nome;
	}

	public function getEmail(){

		return $this->email;
	}

	public function setEmail($email){

		$this->email = $email;
		return $this->email;
	}

	public function getSenha(){

		return $this->senha;
	}

	public function setSenha($senha){

		$this->senha = $senha;
		return $this->senha;
	}

	public function getSexo(){

		return $this->sexo;
	}

	public function setSexo($sexo){

		$this->sexo = $sexo;
		return $this->sexo;
	}

	public function getDataNascimento(){

		return $this->data_nascimento;
	}

	public function setDataNascimento($data_nascimento){

		$this->data_nascimento = $data_nascimento;
		return $this->data_nascimento;
	}

	public function getCpf(){

		return $this->cpf;
	}

	public function setCpf($cpf){

		$this->cpf = $cpf;
		return $this->cpf;
	}

	public function getTelefone(){

		return $this->telefone;
	}

	public function setTelefone($telefone){

		$this->telefone = $telefone;
		return $this->telefone;
	}

	public function getAtivo(){

		return $this->ativo;
	}

	public function setAtivo($ativo){

		$this->ativo = $ativo;
		return $this->ativo;
	}

	public function getDateCreate(){

		return $this->date_create;
	}

	public function setDateCreate($date_create){

		$this->date_create = $date_create;
		return $this->date_create;
	}

}