<?php
/**
 * URL resolver.
 * 
 * It will be called once 404 from main framework app. This controller will be search for URL matched in `url_aliases` DB table.<br>
 * If not found, it will be called to Error 404 controller. If found then it will be send to the specific controller of that alias content type.
 * 
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Controllers;


/**
 * URL resolver controller.
 */
class URLResolverController extends \Rdb\Modules\RdbAdmin\Controllers\BaseController
{


    /**
     * @var \Rdb\System\Config The config class.
     */
    protected $Config;


    /**
     * @var string Current language to check. This property value will be set once called to `isCheckLanguageUrlBase()` method.
     */
    protected $language;


    /**
     * Class constructor.
     * 
     * @param \Rdb\System\Container $Container
     */
    public function __construct(\Rdb\System\Container $Container)
    {
        parent::__construct($Container);

        if ($this->Container->has('Config')) {
            $this->Config = $this->Container->get('Config');
            $this->Config->setModule('');
        } else {
            $this->Config = new \Rdb\System\Config();
        }
    }// __construct


    /**
     * Execute target content controller based on data in DB.
     * 
     * @param \stdClass $resultRow The data that retrieved and found matched URL.
     * @return string Return executed controller result.
     */
    protected function executeTargetContentController(\stdClass $resultRow): string
    {
        $postsContentTypes = ['article', 'page'];
        $taxonomiesContentTypes = ['category', 'tag'];

        if (in_array($resultRow->alias_content_type, $taxonomiesContentTypes)) {
            // if found in taxonomies content types.
            return $this->Modules->execute(
                '\\Rdb\\Modules\\RdbCMSF\\Controllers\\Front\\Taxonomies\\View:index', // must matched in this module config/routes.
                [$resultRow->alias_content_type, $resultRow->alias_content_id]
            );
        } elseif (in_array($resultRow->alias_content_type, $postsContentTypes)) {
            // if found in posts content types.
            return $this->Modules->execute(
                '\\Rdb\\Modules\\RdbCMSF\\Controllers\\Front\\Posts\\View:index', // must matched in this module config/routes.
                [$resultRow->alias_content_type, $resultRow->alias_content_id]
            );
        }

        unset($postsContentTypes, $taxonomiesContentTypes);

        // in this case, found no controllers.
        if ($this->Container->has('Logger')) {
            /* @var $Logger \Rdb\System\Libraries\Logger */
            $Logger = $this->Container->get('Logger');
            $Logger->write(
                'modules/rdbcmsf/controllers/urlresolvercontroller', 
                4, 
                'Couldn\'t found matched alias content type ({content_type}) with the controller.', 
                [
                    'content_type' => $resultRow->alias_content_type,
                    'matchedResult' => $resultRow
                ]
            );
        }

        http_response_code(501);
        return '';
    }// executeTargetContentController


    /**
     * Determine that does it have to check language with URL base.
     * 
     * This is depend on main app config/language.<br>
     * If use URL language method then this will be `true`.
     * 
     * @return bool Return `true` if config was set to use language URL.
     */
    protected function isCheckLanguageUrlBase(): bool
    {
        // detect language url base on the config file.
        if ($this->Config->get('languageMethod', 'language', 'url') === 'cookie') {
            // if config set to detect language using cookie.
            $checkLanguageUrlBase = false;
        } else {
            // if config set to detect language using URL
            $checkLanguageUrlBase = true;
        }

        if ($checkLanguageUrlBase === true) {
            $this->language = ($_SERVER['RUNDIZBONES_LANGUAGE'] ?? $this->Config->getDefaultLanguage());
        }

        return $checkLanguageUrlBase;
    }// isCheckLanguageUrlBase


    /**
     * Resolve the URL with `url_aliases` DB table.
     * 
     * @return string
     */
    public function resolveAction()
    {
        $Url = new \Rdb\System\Libraries\Url($this->Container);
        $requestURL = ltrim($Url->getCurrentUrlRelatedFromPublic(), '/');
        unset($Url);

        $checkLanguageUrlBase = $this->isCheckLanguageUrlBase();

        // also get errors config from main framework app for later use.
        $errors = $this->Config->get('ALL', 'error', []);

        // check for matched URL and retrieve the data from DB.
        $UrlAliasesDb = new \Rdb\Modules\RdbCMSA\Models\UrlAliasesDb($this->Container);
        $where = [];
        $where['alias_url_encoded'] = $requestURL;
        if ($checkLanguageUrlBase === true) {
            $where['language'] = $this->language;
        }
        $resultRow = $UrlAliasesDb->get($where);
        unset($checkLanguageUrlBase, $UrlAliasesDb, $where);

        // check the result that found, not found, found but match with redirection or something else.
        if (is_object($resultRow)) {
            // if found matched.
            if (!empty($resultRow->alias_content_type) && !empty($resultRow->alias_content_id)) {
                // if found matched URL with content type.
                unset($errors);

                return $this->executeTargetContentController($resultRow);
            } elseif (!empty($resultRow->alias_redirect_to) || !empty($resultRow->alias_redirect_to_encoded)) {
                // if found matched URL with redirection.
                unset($errors);

                $this->responseNoCache();
                http_response_code($resultRow->alias_redirect_code);
                header('Location: ' . $resultRow->alias_redirect_to_encoded);
                exit();
            } else {
                $notFound = true;
            }
        } else {
            // if not found.
            $notFound = true;
        }

        if (isset($notFound) && $notFound === true) {
            return $this->Modules->execute($errors['404'], func_get_args());
        }
    }// resolveAction


}
