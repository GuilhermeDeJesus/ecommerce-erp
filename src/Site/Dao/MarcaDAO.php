<?php
namespace Store\Site\Dao;

use Store\Core\Dao\MarcaCoreDAO;
require_once 'src/Core/Dao/MarcaCoreDAO.php';

class MarcaDAO extends MarcaCoreDAO
{

    public static function getIdPorNome($nome)
    {
        $_m = self::select([
            'id'
        ], [
            'nome',
            '=',
            $nome
        ]);

        if (count($_m) != 0) {
            return $_m[0]['id'];
        }
    }
}