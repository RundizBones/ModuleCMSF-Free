<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Controllers\Front\Files;


/**
 * View file detail  controller.
 */
class ViewController extends \Rdb\Modules\RdbCMSF\Controllers\RdbCMSFBaseController
{


    /**
     * View file page.
     * 
     * @param string $file_id
     * @return string
     */
    public function indexAction(string $file_id = ''): string
    {
        $Url = new \Rdb\System\Libraries\Url($this->Container);

        $output = [];

        $output['configDb'] = $this->getConfigDb();

        $output['file_id'] = $file_id;

        // get file. ----------------------------------------
        $FilesDb = new \Rdb\Modules\RdbCMSA\Models\FilesDb($this->Container);
        $FilesSubController = new \Rdb\Modules\RdbCMSA\Controllers\Admin\SubControllers\Files\FilesSubController();
        $where = [
            'file_id' => $file_id,
            'files.file_status' => 1,
            'files.file_visibility' => 1,
        ];
        $File = $FilesDb->get($where);
        unset($where);

        if (empty($File)) {
            // if not found selected file.
            return $this->response404(func_get_args());
        }

        $output['File'] = $File;
        $output['File']->originalFullURL = $Url->getDomainProtocol() 
            . $Url->getPublicUrl() 
            . (!empty($FilesDb->rootPublicFolderName) ? '/' . $FilesDb->rootPublicFolderName : '')
            . '/' . $FilesDb->getFileRelatePath($File);
        $output['FilesSubController'] = $FilesSubController;

        $output['pageTitle'] = $File->file_media_name;
        $output['pageHtmlTitle'] = $this->getPageHtmlTitle($output['pageTitle'], $output['configDb']['rdbadmin_SiteName']);
        $output['pageHtmlClasses'] = $this->getPageHtmlClasses();
        unset($File, $FilesDb, $FilesSubController);


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
        return $this->Views->render('Front/Files/View/index_v', $output);
    }// indexAction


}
