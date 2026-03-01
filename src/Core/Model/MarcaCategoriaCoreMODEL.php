<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class MarcaCategoriaCoreMODEL 
{

	public $id_marca = "id_marca";
	public $id_categoria = "id_categoria";

	public function __construct(){}

	public function getIdMarca(){

		return $this->id_marca;
	}

	public function setIdMarca($id_marca){

		$this->id_marca = $id_marca;
		return $this->id_marca;
	}

	public function getIdCategoria(){

		return $this->id_categoria;
	}

	public function setIdCategoria($id_categoria){

		$this->id_categoria = $id_categoria;
		return $this->id_categoria;
	}

}