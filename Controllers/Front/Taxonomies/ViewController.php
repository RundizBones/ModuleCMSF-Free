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
        $postsPerPage = 10;
        $currentOffset = (int) trim($this->Input->get('offset', 0));
        $PostsDb = new \Rdb\Modules\RdbCMSA\Models\PostsDb($this->Container);
        $options = [];
        $options['where'] = [
            'posts.language' => ($_SERVER['RUNDIZBONES_LANGUAGE'] ?? 'th'),
        ];
        $options['tidsIn'] = [$tid];
        $options['isPublished'] = true;
        $options['sortOrders'] = [
            ['sort' => 'post_publish_date', 'order' => 'desc'],
        ];
        $options['limit'] = $postsPerPage;
        $options['offset'] = $currentOffset;
        $options['skipCategories'] = true;
        $options['skipTags'] = true;
        $listPosts = $PostsDb->listItems($options);
        unset($options, $PostsDb);
        $output['listPosts'] = ($listPosts['items'] ?? []);

        $totalPosts = $listPosts['total'];
        $totalPages = ceil($totalPosts / $postsPerPage);
        $previousOffset = max(0, ($currentOffset - 1));
        $nextOffset = min($totalPages, ($currentOffset + 1));
        $output['paginations'] = [
            'previous' => $Url->getCurrentUrl(true) . '?offset=' . $previousOffset,
            'next' => $Url->getCurrentUrl(true) . '?offset=' . $nextOffset,
        ];
        unset($currentOffset, $listPosts, $nextOffset, $postsPerPage, $previousOffset, $totalPages, $totalPosts);

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
