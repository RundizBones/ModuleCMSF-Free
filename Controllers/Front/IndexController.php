<?php
/**
 * CMS front index.
 * 
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Controllers\Front;


/**
 * CMS front index controller
 */
class IndexController extends \Rdb\Modules\RdbCMSF\Controllers\RdbCMSFBaseController
{


    public function indexAction()
    {
        $Url = new \Rdb\System\Libraries\Url($this->Container);
        $output = [];

        $output['configDb'] = $this->getConfigDb();
        $output['pageTitle'] = d__('rdbcmsf', 'Home');
        $output['pageHtmlTitle'] = $this->getPageHtmlTitle($output['pageTitle'], $output['configDb']['rdbadmin_SiteName']);
        $output['pageHtmlClasses'] = $this->getPageHtmlClasses();

        // display, response part ---------------------------------------------------------------------------------------------
        // get module's assets
        $ModuleAssets = new \Rdb\Modules\RdbCMSF\ModuleData\ModuleAssets($this->Container);
        $moduleAssetsData = $ModuleAssets->getModuleAssets();
        unset($ModuleAssets);
        // Assets class for add CSS and JS.
        $Assets = new \Rdb\Modules\RdbAdmin\Libraries\Assets($this->Container);

        $Assets->addMultipleAssets('css', ['bootstrap4'], $moduleAssetsData);
        $Assets->addMultipleAssets('js', ['bootstrap4'], $moduleAssetsData);

        // set classes variables for use in views.
        $output['Assets'] = $Assets;
        $output['Modules'] = $this->Modules;
        $output['Url'] = $Url;
        $output['Views'] = $this->Views;

        unset($Assets, $rdbAdminAssets, $Url);
        return $this->Views->render('Front/Index/index_v', $output);
    }// indexAction


}
