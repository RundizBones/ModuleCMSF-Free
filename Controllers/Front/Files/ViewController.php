<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Controllers\Front\Files;


/**
 * View file detail  controller.
 */
class ViewController extends \Rdb\Modules\RdbAdmin\Controllers\BaseController
{


    public function indexAction(string $file_id = '')
    {
        echo 'file id: ' . $file_id;
        echo '<br>' . PHP_EOL;
    }// indexAction


}
