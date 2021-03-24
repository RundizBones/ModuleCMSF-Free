<?php
/**
 * Name: Change front page URLs
 * URL: https://rundiz.com
 * Version: 0.0.1
 * Description: Detect the correct content ID of selected language and return its URL.
 * Author: Vee W.
 * Author URL: http://rundiz.com
 * 
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Plugins\ChangeURLs;


/**
 * Change URLs hooks.
 */
class ChangeURLs implements \Rdb\Modules\RdbAdmin\Interfaces\Plugins
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
    public function disable()
    {
    }// disable


    /**
     * {@inheritDoc}
     */
    public function enable()
    {
    }// enable


    /**
     * {@inheritDoc}
     */
    public function registerHooks()
    {
        if ($this->Container->has('Plugins')) {
            /* @var $Plugins \Rdb\Modules\RdbAdmin\Libraries\Plugins */
            $Plugins = $this->Container->get('Plugins');
            $FrontPostsURLs = new FrontPostsURLs($this->Container);
            $Plugins->addHook('Rdb\Modules\Languages\Controllers\LanguagesController->updateAction.afterGetRedirectUrl', [$FrontPostsURLs, 'detectFrontURLs'], 10);
            unset($FrontPostsURLs, $Plugins);
        }
    }// registerHooks


    /**
     * {@inheritDoc}
     */
    public function uninstall()
    {
    }// uninstall


}
