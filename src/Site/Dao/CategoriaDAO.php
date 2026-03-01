<?php
namespace Store\Site\Dao;

use Store\Core\Dao\CategoriaCoreDAO;
use Doctrine\Common\Collections\Selectable;
require_once 'src/Core/Dao/CategoriaCoreDAO.php';

class CategoriaDAO extends CategoriaCoreDAO
{

    public static function getDescricaoResult($data)
    {
        if (count($data) != 0) {
            return $data[0]['descricao'];
        }
    }

    public static function addChildren($children)
    {
        return self::select([
            '*'
        ], [
            'categoria_pai',
            '=',
            $children
        ]);
    }

    public static function addFather($father)
    {
        return self::select([
            '*'
        ], [
            'id',
            '=',
            $father
        ]);
    }

    public static function getSubcategorias($father)
    {
        $_f = self::select([
            '*'
        ], [
            'descricao',
            '=',
            $father
        ]);

        $where = null;
        if (isset($_f[0]['id'])) {
            $where = [
                'categoria_pai',
                '=',
                $_f[0]['id']
            ];
        }

        return self::select([
            '*'
        ], $where);
    }

    public static function getIdPorDescricao($descricao)
    {
        $_f = self::select([
            'id'
        ], [
            'descricao',
            '=',
            $descricao
        ]);

        if (count($_f) != 0) {
            return $_f[0]['id'];
        }
    }

    public static function getCategoriaFather($idPai)
    {
        $_f = self::select([
            '*'
        ], [
            'id',
            '=',
            $idPai
        ]);

        if (count($_f) != 0) {
            return $_f[0]['descricao'] . ' > ';
        }
    }
}