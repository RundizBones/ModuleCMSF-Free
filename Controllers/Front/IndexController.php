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


    /**
     * View home page.
     * 
     * @return string
     */
    public function indexAction(): string
    {
        $Url = new \Rdb\System\Libraries\Url($this->Container);

        $output = [];

        $output['configDb'] = $this->getConfigDb();
        $output['pageTitle'] = d__('rdbcmsf', 'Home');
        $output['pageHtmlTitle'] = $this->getPageHtmlTitle($output['pageTitle'], $output['configDb']['rdbadmin_SiteName']);
        $output['pageHtmlClasses'] = $this->getPageHtmlClasses();

        $output['controllerPath'] = __FILE__;

        // list pages. --------------------------------------------------------
        $PostsDb = new \Rdb\Modules\RdbCMSA\Models\PostsDb($this->Container);
        $options = [];
        $options['where'] = [
            'post_type' => 'page',
            'posts.language' => ($_SERVER['RUNDIZBONES_LANGUAGE'] ?? 'th'),
        ];
        $options['isPublished'] = true;
        $options['unlimited'] = true;
        $options['skipCategories'] = true;
        $options['skipTags'] = true;
        $listPages = $PostsDb->listItems($options);
        unset($options, $PostsDb);
        $output['listPages'] = ($listPages['items'] ?? []);

        // display, response part ---------------------------------------------------------------------------------------------
        // get module's assets
        $ModuleAssets = new \Rdb\Modules\RdbCMSF\ModuleData\ModuleAssets($this->Container);
        $moduleAssetsData = $ModuleAssets->getModuleAssets();
        unset($ModuleAssets);
        // Assets class for add CSS and JS.
        $Assets = new \Rdb\Modules\RdbAdmin\Libraries\Assets($this->Container);

        $Assets->addMultipleAssets('css', ['bootstrap4', 'smartmenus-bootstrap'], $moduleAssetsData);
        $Assets->addMultipleAssets('js', ['bootstrap4', 'smartmenus-bootstrap'], $moduleAssetsData);

        // set classes variables for use in views.
        $output['Assets'] = $Assets;
        $output['Modules'] = $this->Modules;
        $output['Url'] = $Url;
        $output['Views'] = $this->Views;

        require_once MODULE_PATH . '/RdbAdmin/Helpers/LanguagesFunctions.php';

        unset($Assets, $moduleAssetsData, $Url);
        return $this->Views->render('Front/Index/index_v', $output);
    }// indexAction


}
