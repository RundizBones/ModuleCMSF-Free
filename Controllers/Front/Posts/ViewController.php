<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Controllers\Front\Posts;


/**
 * View post detail controller.
 */
class ViewController extends \Rdb\Modules\RdbCMSF\Controllers\RdbCMSFBaseController
{


    public function indexAction(string $post_type = '', string $post_id = '')
    {
        echo $post_type . '/' . $post_id;
        echo '<br>' . PHP_EOL;
        echo $_SERVER['REQUEST_URI'] . '<br>' . PHP_EOL;
        if (isset($_SERVER['RUNDIZBONES_MODULEEXECUTE'])) {
            echo 'module execute?:' . PHP_EOL;
            var_export($_SERVER['RUNDIZBONES_MODULEEXECUTE']);
            echo '<br>' . PHP_EOL;
        }
    }// indexAction


}
