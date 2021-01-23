<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\ModuleData;


/**
 * Module assets data.
 */
class ModuleAssets
{


    /**
     * @var \Rdb\System\Container
     */
    protected $Container;


    /**
     * Class constructor.
     * 
     * @param \Rdb\System\Container $Container The DI container class.
     */
    public function __construct(\Rdb\System\Container $Container)
    {
        $this->Container = $Container;
    }// __construct


    /**
     * Get module's assets list.
     * 
     * @see \Rdb\Modules\RdbAdmin\Libraries\Assets::addMultipleAssets() See <code>\Rdb\Modules\RdbAdmin\Libraries\Assets</code> class at <code>addMultipleAssets()</code> method for data structure.
     * @return array Return associative array with `css` and `js` key with its values.
     */
    public function getModuleAssets(): array
    {
        $Url = new \Rdb\System\Libraries\Url($this->Container);
        $publicModuleUrl = $Url->getPublicModuleUrl(__FILE__);
        unset($Url);

        return [
            'css' => [
                [
                    'handle' => 'bootstrap4',
                    'file' => 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css?v=460',
                    'version' => '4.6.0',
                    'attributes' => [
                        'crossorigin' => 'anonymous', 
                        'integrity' => 'sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l'
                    ],
                ],
            ],
            'js' => [
                [
                    'handle' => 'jquery3',
                    'file' => 'https://code.jquery.com/jquery-3.5.1.slim.min.js',
                    'version' => '3.5.1',
                    'attributes' => [
                        'crossorigin' => 'anonymous', 
                        'integrity' => 'sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj',
                    ],
                ],
                [
                    'handle' => 'bootstrap4',
                    'file' => 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js',
                    'version' => '4.6.0',
                    'dependency' => ['jquery3'],
                    'attributes' => [
                        'crossorigin' => 'anonymous', 
                        'integrity' => 'sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns',
                    ],
                ],
            ],
        ];
    }// getModuleAssets


}
