<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\ModuleData;


/**
 * The module admin class for set permissions, menu items.
 */
class ModuleAdmin implements \Rdb\Modules\RdbAdmin\Interfaces\ModuleAdmin
{


    /**
     * @var \Rdb\System\Container
     */
    protected $Container;


    /**
     * {@inheritDoc}
     */
    public function __construct(\Rdb\System\Container $Container)
    {
        $this->Container = $Container;
    }// __construct


    /**
     * {@inheritDoc}
     */
    public function dashboardWidgets(): array
    {
        return [];
    }// dashboardWidgets


    /**
     * {@inheritDoc}
     */
    public function definePermissions(): array
    {
        return [];
    }// definePermissions


    /**
     * {@inheritDoc}
     */
    public function permissionDisplayText(string $key = '', bool $translate = false)
    {
        if (!empty($key)) {
            return '';
        } else {
            return [];
        }
    }// permissionDisplayText


    /**
     * {@inheritDoc}
     */
    public function menuItems(): array
    {
        $Url = new \Rdb\System\Libraries\Url($this->Container);

        // declare language object, set text domain to make sure that this is translation for your module.
        if ($this->Container->has('Languages')) {
            $Languages = $this->Container->get('Languages');
        } else {
            $Languages = new \Rdb\Modules\RdbAdmin\Libraries\Languages($this->Container);
        }
        $Languages->bindTextDomain(
            'rdbcmsf', 
            dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'RdbCMSF' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR . 'translations'
        );
        $Languages->getHelpers();

        $urlBaseWithLang = $Url->getAppBasedPath(true);
        $urlBase = $Url->getAppBasedPath();

        return [
            1 => [
                'id' => 'rdbcmsf-visit-front-pages',
                'icon' => 'fas fa-home fa-fw',
                'name' => d__('rdbcmsf', 'Visit site'),
                'link' => $urlBaseWithLang . '/',
            ],
        ];
    }// menuItems


}
