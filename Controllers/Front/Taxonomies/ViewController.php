<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Controllers\Front\Taxonomies;


/**
 * View taxonomy detail controller. This maybe posts listing page in selected taxonomy.
 */
class ViewController extends \Rdb\Modules\RdbAdmin\Controllers\BaseController
{


    public function indexAction(string $t_type = '', string $tid = '')
    {
        echo $t_type . '/' . $tid;
        echo '<br>' . PHP_EOL;
    }// indexAction


}
