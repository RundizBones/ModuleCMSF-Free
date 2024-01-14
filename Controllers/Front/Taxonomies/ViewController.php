<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Controllers\Front\Taxonomies;


/**
 * View taxonomy detail controller. This maybe posts listing page in selected taxonomy.
 */
class ViewController extends \Rdb\Modules\RdbCMSF\Controllers\RdbCMSFBaseController
{


    use \Rdb\Modules\RdbCMSF\Controllers\Front\Traits\PostsTrait;


    /**
     * View taxonomy page.
     * 
     * @param string $t_type
     * @param string $tid
     * @return string
     */
    public function indexAction(string $t_type = '', string $tid = ''): string
    {
        $Url = new \Rdb\System\Libraries\Url($this->Container);

        $output = [];

        $output['configDb'] = $this->getConfigDb();

        $output['t_type'] = $t_type;
        $output['tid'] = $tid;

        // get category details. ---------------------------
        $CategoriesDb = new \Rdb\Modules\RdbCMSA\Models\CategoriesDb($this->Db->PDO(), $this->Container);
        $where = [
            'tid' => $tid,
            'taxonomy_term_data.language' => ($_SERVER['RUNDIZBONES_LANGUAGE'] ?? 'th'),
            't_type' => $t_type,
            't_status' => '1',
        ];
        $Category = $CategoriesDb->get($where);
        unset($CategoriesDb, $where);

        if (empty($Category)) {
            // if not found selected category.
            return $this->response404(func_get_args());
        }
        $output['Category'] = $Category;

        $output['pageTitle'] = $Category->t_name;
        $output['pageHtmlTitle'] = $this->getPageHtmlTitle($output['pageTitle'], $output['configDb']['rdbadmin_SiteName']);
        $output['pageHtmlClasses'] = $this->getPageHtmlClasses();
        unset($Category);

        // list posts. ----------------------------------------
        $output = array_merge($output, $this->listPublishedPosts($tid));

        // process canonical link.
        $canonicalLink = $Url->getDomainProtocol() . $Url->getAppBasedPath(true) . '/taxonomies/' . rawurlencode($t_type) . '/' . $tid;
        header('Link: ' . $canonicalLink . '; rel="canonical"');
        $pageHtmlHead = '<link rel="canonical" href="' . $canonicalLink . '">';
        unset($canonicalLink);

        $output['pageHtmlHead'] = $pageHtmlHead;

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
        return $this->Views->render('Front/Taxonomies/View/index_v', $output);
    }// indexAction


}
