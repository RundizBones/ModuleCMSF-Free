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


    /**
     * View post page.
     * 
     * @param string $post_type
     * @param string $post_id
     * @return string
     */
    public function indexAction(string $post_type = '', string $post_id = ''): string
    {
        $Url = new \Rdb\System\Libraries\Url($this->Container);

        $output = [];

        $output['configDb'] = $this->getConfigDb();

        $output['post_type'] = $post_type;
        $output['post_id'] = $post_id;

        // get post. ----------------------------------------
        $PostsDb = new \Rdb\Modules\RdbCMSA\Models\PostsDb($this->Container);
        $where = [
            'posts.post_id' => $post_id,
            'posts.post_type' => $post_type,
            'posts.language' => ($_SERVER['RUNDIZBONES_LANGUAGE'] ?? 'th'),
        ];
        $options = [];
        $options['isPublished'] = true;
        $Post = $PostsDb->get($where, $options);
        unset($options, $PostsDb, $where);

        if (empty($Post)) {
            // if not found selected post.
            return $this->response404(func_get_args());
        }

        $output['Post'] = $Post;

        $output['pageTitle'] = $Post->post_name;
        $output['pageHtmlTitle'] = $this->getPageHtmlTitle($output['pageTitle'], $output['configDb']['rdbadmin_SiteName']);
        $output['pageHtmlClasses'] = $this->getPageHtmlClasses();
        $output['pageHtmlHead'] = $Post->revision_head_value;
        unset($Post);

        // process canonical link.
        $canonicalLink = $Url->getDomainProtocol() . $Url->getAppBasedPath(true) . '/posts/' . rawurlencode($post_type) . '/' . $post_id;
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

        $Assets->addMultipleAssets('css', ['rdbcmsf-style', 'smartmenus-bootstrap'], $moduleAssetsData);
        $Assets->addMultipleAssets('js', ['bootstrap4', 'smartmenus-bootstrap'], $moduleAssetsData);

        // set classes variables for use in views.
        $output['Assets'] = $Assets;
        $output['Modules'] = $this->Modules;
        $output['Url'] = $Url;
        $output['Views'] = $this->Views;

        require_once MODULE_PATH . '/RdbAdmin/Helpers/LanguagesFunctions.php';

        unset($Assets, $moduleAssetsData, $Url);
        return $this->Views->render('Front/Posts/View/index_v', $output);
    }// indexAction


}
