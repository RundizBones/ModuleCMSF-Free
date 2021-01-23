<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Controllers;


/**
 * RundizBones CMS front pages module - overall base controller.
 * 
 * Use this controller to automatically bind text domain for translation.
 */
class RdbCMSFBaseController extends \Rdb\Modules\RdbAdmin\Controllers\BaseController
{


    /**
     * {@inheritDoc}
     */
    public function __construct(\Rdb\System\Container $Container)
    {
        parent::__construct($Container);

        // bind text domain file and you can use translation with functions that work for specific domain such as `d__()`.
        $this->Languages->bindTextDomain(
            'rdbcmsf', 
            MODULE_PATH . DIRECTORY_SEPARATOR . 'RdbCMSF' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'translations'
        );
    }// __construct


    /**
     * Get config from DB.
     * 
     * This will get commonly used between front pages controllers with these data.
     * <pre>
     * rdbadmin_SiteName,
     * rdbadmin_SiteTimezone,
     * </pre>
     * 
     * @return array
     */
    protected function getConfigDb(): array
    {
        $ConfigDb = new \Rdb\Modules\RdbAdmin\Models\ConfigDb($this->Container);
        $configNames = [
            'rdbadmin_SiteName',
            'rdbadmin_SiteTimezone',
        ];
        $configDefaults = [
            '',
            'Asia/Bangkok',
        ];

        $output = $ConfigDb->get($configNames, $configDefaults);
        unset($ConfigDb, $configDefaults, $configNames);

        return $output;
    }// getConfigDb


}
