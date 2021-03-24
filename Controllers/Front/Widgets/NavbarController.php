<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Controllers\Front\Widgets;


/**
 * Navbar widget controller.
 */
class NavbarController extends \Rdb\Modules\RdbCMSF\Controllers\RdbCMSFBaseController
{


    /**
     * Render the navigation bar.
     * 
     * @return string
     */
    public function renderAction(): string
    {
        $Url = new \Rdb\System\Libraries\Url($this->Container);
        $_SERVER['HTTP_ACCEPT'] = 'text/html';

        $CategoriesDb = new \Rdb\Modules\RdbCMSA\Models\CategoriesDb($this->Db->PDO(), $this->Container);
        $options = [];
        $options['where'] = [
            'language' => ($_SERVER['RUNDIZBONES_LANGUAGE'] ?? 'th'),
            't_type' => 'category',
            't_status' => '1',
        ];
        $options['unlimited'] = true;
        $listCategories = $CategoriesDb->listRecursive($options);
        unset($CategoriesDb, $options);

        $output = [];
        $output['urls'] = [
            'home' => (!empty($Url->getPublicUrl()) ? $Url->getPublicUrl() : '/'),
            'currentUrl' => (!empty($Url->getCurrentUrl()) ? $Url->getCurrentUrl() : '/'),
        ];

        $output['categories'] = ($listCategories['items'] ?? []);
        unset($listCategories);

        // get languages.
        $languages = $this->Modules->execute('\\Rdb\\Modules\\Languages\\Controllers\\Languages:index');
        $Serializer = new \Rundiz\Serializer\Serializer();
        $output['languages'] = $Serializer->maybeUnserialize($languages);
        unset($languages, $Serializer);

        // display, response part ---------------------------------------------------------------------------------------------
        // get module's assets
        $ModuleAssets = new \Rdb\Modules\RdbCMSF\ModuleData\ModuleAssets($this->Container);
        $moduleAssetsData = $ModuleAssets->getModuleAssets();
        unset($ModuleAssets);
        // Assets class for add CSS and JS.
        $Assets = new \Rdb\Modules\RdbAdmin\Libraries\Assets($this->Container);

        $Assets->addMultipleAssets('js', ['languageSwitcher'], $moduleAssetsData);
        $Assets->addJsObject(
            'languageSwitcher',
            'LanguageSwitcherObject',
            [
                'currentUrl' => $output['urls']['currentUrl'] . $Url->getQuerystring(),
                'currentLanguageID' => ($_SERVER['RUNDIZBONES_LANGUAGE'] ?? ''),
                'setLanguage_method' => $output['languages']['setLanguage_method'],
                'setLanguage_url' => $output['languages']['setLanguage_url'],
            ]
        );

        // set classes variables for use in views.
        $output['Assets'] = $Assets;
        $output['Modules'] = $this->Modules;
        $output['Url'] = $Url;
        $output['Views'] = $this->Views;

        require_once MODULE_PATH . '/RdbAdmin/Helpers/LanguagesFunctions.php';
        require_once dirname(__DIR__, 3) . '/Helpers/menuHelpers.php';

        unset($Assets, $moduleAssetsData, $Url);
        return $this->Views->render('Front/Widgets/Navbar/render_v', $output);
    }// renderAction


}
