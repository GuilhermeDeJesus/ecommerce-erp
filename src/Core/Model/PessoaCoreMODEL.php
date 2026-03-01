<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class PessoaCoreMODEL 
{

	public $id = "id";
	public $tipo = "tipo";
	public $nome = "nome";
	public $cpf = "cpf";
	public $cnpj = "cnpj";
	public $cep = "cep";
	public $celular = "celular";
	public $telefone = "telefone";
	public $site = "site";
	public $data_nascimento = "data_nascimento";
	public $email = "email";
	public $ativo = "ativo";
	public $observacao = "observacao";
	public $senha = "senha";
	public $id_classe = "id_classe";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getTipo(){

		return $this->tipo;
	}

	public function setTipo($tipo){

		$this->tipo = $tipo;
		return $this->tipo;
	}

	public function getNome(){

		return $this->nome;
	}

	public function setNome($nome){

		$this->nome = $nome;
		return $this->nome;
	}

	public function getCpf(){

		return $this->cpf;
	}

	public function setCpf($cpf){

		$this->cpf = $cpf;
		return $this->cpf;
	}

	public function getCnpj(){

		return $this->cnpj;
	}

	public function setCnpj($cnpj){

		$this->cnpj = $cnpj;
		return $this->cnpj;
	}

	public function getCep(){

		return $this->cep;
	}

	public function setCep($cep){

		$this->cep = $cep;
		return $this->cep;
	}

	public function getCelular(){

		return $this->celular;
	}

	public function setCelular($celular){

		$this->celular = $celular;
		return $this->celular;
	}

	public function getTelefone(){

		return $this->telefone;
	}

	public function setTelefone($telefone){

		$this->telefone = $telefone;
		return $this->telefone;
	}

	public function getSite(){

		return $this->site;
	}

	public function setSite($site){

		$this->site = $site;
		return $this->site;
	}

	public function getDataNascimento(){

		return $this->data_nascimento;
	}

	public function setDataNascimento($data_nascimento){

		$this->data_nascimento = $data_nascimento;
		return $this->data_nascimento;
	}

	public function getEmail(){

		return $this->email;
	}

	public function setEmail($email){

		$this->email = $email;
		return $this->email;
	}

	public function getAtivo(){

		return $this->ativo;
	}

	public function setAtivo($ativo){

		$this->ativo = $ativo;
		return $this->ativo;
	}

	public function getObservacao(){

		return $this->observacao;
	}

	public function setObservacao($observacao){

		$this->observacao = $observacao;
		return $this->observacao;
	}

	public function getSenha(){

		return $this->senha;
	}

	public function setSenha($senha){

		$this->senha = $senha;
		return $this->senha;
	}

	public function getIdClasse(){

		return $this->id_classe;
	}

	public function setIdClasse($id_classe){

		$this->id_classe = $id_classe;
		return $this->id_classe;
	}

}