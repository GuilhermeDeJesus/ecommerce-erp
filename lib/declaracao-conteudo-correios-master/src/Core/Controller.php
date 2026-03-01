<?php
namespace Click4Web\DeclaracaoConteudo\Core;

/**
 * Class Controller
 *
 * @package    Click4Web\DeclaracaoConteudo
 * @subpackage Core
 * @author     fontebasso <sfdsilva@fontesoft.com>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
class Controller
{
    /**
     * Carrega uma view
     *
     * @param $name
     * @param array $vars
     *
     * @return string
     */
    public function view($name, $vars = [])
    {
        extract($vars);
        $ds = DIRECTORY_SEPARATOR;
        ob_start();
        include __DIR__ . $ds . '..' . $ds . 'Resources'. $ds .'views' . $ds . $name . '.php';
        return ob_get_clean();
    }
}
