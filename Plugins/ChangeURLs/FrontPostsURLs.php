<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rdb\Modules\RdbCMSF\Plugins\ChangeURLs;


/**
 * Detect and change to its correct posts URLs.
 * 
 * @since 0.0.2
 */
class FrontPostsURLs
{


    /**
     * @var \Rdb\System\Container
     */
    protected $Container;


    /**
     * @var string
     */
    protected $logChannel = '';


    /**
     * @var \Rdb\System\Libraries\Logger
     */
    protected $Logger;


    /**
     * Class constructor.
     */
    public function __construct(\Rdb\System\Container $Container)
    {
        $this->Container = $Container;
    }// __construct


    /**
     * Detect front URLs and change to the correct URL using translation matcher.
     * 
     * @param string $redirectUrl
     * @param string $currentUrl
     * @param string $configLanguageMethod
     * @param bool $configLanguageUrlDefaultVisible
     * @param string $defaultLanguage
     * @param string $languageID
     * @param string $currentLanguageID
     * @return string
     */
    public function detectFrontURLs(
        $redirectUrl, 
        $currentUrl, 
        $configLanguageMethod, 
        $configLanguageUrlDefaultVisible, 
        $defaultLanguage, 
        $languageID,
        $currentLanguageID
    ) {
        if ($this->Container->has('Logger')) {
            /* @var $Logger \Rdb\System\Libraries\Logger */
            $this->Logger = $this->Container->get('Logger');
            $this->logChannel = 'modules/rdbcmsf/plugins/changeurls/frontpostsurls/detectfronturls';
            $this->Logger->write($this->logChannel, 0, 'Change URLs plugin is working.');
        }

        require_once MODULE_PATH . DIRECTORY_SEPARATOR . 'Languages' . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'multibyte.php';

        $Url = new \Rdb\System\Libraries\Url($this->Container);
        $appBase = $Url->getAppBasedPath() . '/';
        $removedAppBaseUrl = mb_substr_replace($redirectUrl, '', 0, mb_strlen($appBase));
        $detectedLanguageID = '';
        if (stripos($removedAppBaseUrl, $languageID . '/') === 0) {
            // if still found language-id/ in the URL.
            $detectedLanguageID = $languageID . '/';
            $removedAppBaseUrl = mb_substr_replace($removedAppBaseUrl, '', 0, mb_strlen($detectedLanguageID));
        }
        $expRemovedAppBaseUrl = explode('/', $removedAppBaseUrl);

        if (isset($expRemovedAppBaseUrl[0]) && strtolower($expRemovedAppBaseUrl[0]) === 'admin') {
            // if it is admin page.
            if (isset($this->Logger)) {
                $this->Logger->write($this->logChannel, 0, 'The first URL segment is admin, do not hook here.', $expRemovedAppBaseUrl);
            }

            return ;
        } else {
            // if NOT admin page.
            if (!isset($expRemovedAppBaseUrl[2]) || !is_numeric($expRemovedAppBaseUrl[2])) {
                // if the 3rd segment is not number.
                // not one of these
                // /posts/article/nn
                // /posts/page/nn
                // /taxonomies/categories/nn
                // /taxonomies/tags/nn

                // get the ID of this content by look up in URL alias DB table.
                $aliasResult = $this->lookupURLAliases($currentLanguageID, $removedAppBaseUrl);
                if (isset($this->Logger)) {
                    $this->Logger->write($this->logChannel, 0, 'Look up URL aliases of content, here is the arguments.', [$currentLanguageID, $removedAppBaseUrl]);
                    $this->Logger->write($this->logChannel, 0, 'Look up URL aliases of content, here is the result.', (is_array($aliasResult) ? $aliasResult : [$aliasResult]));
                }

                if (
                    is_array($aliasResult) &&
                    array_key_exists('content_id', $aliasResult) &&
                    array_key_exists('content_type', $aliasResult) &&
                    array_key_exists('tm_table', $aliasResult)
                ) {
                    $tmTable = $aliasResult['tm_table'];
                    $dataID = $aliasResult['content_id'];
                    if (strtolower($tmTable) === 'taxonomy_term_data') {
                        $removedAppBaseUrl = 'taxonomies';
                    } elseif (strtolower($tmTable) === 'posts') {
                        $removedAppBaseUrl = 'posts';
                    }
                    $removedAppBaseUrl .= '/' . $aliasResult['content_type'] . '/' . $dataID;
                }
                unset($aliasResult);
            } else {
                // if the 3rd segment is number.
                $dataID = $expRemovedAppBaseUrl[2];
                if (
                    isset($expRemovedAppBaseUrl[0]) && 
                    (
                        strtolower($expRemovedAppBaseUrl[0]) === 'posts'
                    )
                ) {
                    // if first url segment is posts.
                    // get translation matched in posts.
                    $tmTable = 'posts';
                } elseif (
                    isset($expRemovedAppBaseUrl[0]) && 
                    (
                        strtolower($expRemovedAppBaseUrl[0]) === 'taxonomies'
                    )
                ) {
                    // if first url segment is taxonomies.
                    // get translation matched in taxonomy_term_data.
                    $tmTable = 'taxonomy_term_data';
                }// endif check 1st segment is posts or taxonomy.
            }// endif check 3rd segment is number or not.

            if (isset($tmTable) && isset($dataID)) {
                // if it was be able to detected content ID (data ID) properly.
                return $this->getTranslationMatchesForContents(
                    $redirectUrl, 
                    $currentUrl, 
                    $defaultLanguage, 
                    $languageID,
                    $detectedLanguageID,
                    $appBase,
                    $dataID,
                    $tmTable,
                    $removedAppBaseUrl
                );
            }
        }// endif; check admin page or not.

        unset($appBase, $expRemovedAppBaseUrl, $removedAppBaseUrl, $Url);
    }// detectFrontURLs


    /**
     * Get translation matches for contents.
     * 
     * @param string $redirectUrl
     * @param string $currentUrl
     * @param string $defaultLanguage
     * @param string $languageID
     * @param string $detectedLanguageID
     * @param string $appBase
     * @param int $dataID
     * @param string $tmTable
     * @param string $removedAppBaseUrl
     * @return string|null
     */
    protected function getTranslationMatchesForContents(
        $redirectUrl, 
        $currentUrl, 
        $defaultLanguage, 
        $languageID,
        string $detectedLanguageID,
        string $appBase,
        int $dataID,
        string $tmTable,
        string $removedAppBaseUrl
    ) {
        $TranslationMatcherDb = new \Rdb\Modules\RdbCMSA\Models\TranslationMatcherDb($this->Container);
        $where = [];
        $where['findDataIds'] = [$dataID];
        $where['tm_table'] = $tmTable;
        $result = $TranslationMatcherDb->get($where);
        unset($TranslationMatcherDb, $where);

        if (isset($result) && is_object($result) && !empty($result)) {
            $matches = json_decode($result->matches);

            if (isset($matches->{$languageID})) {
                $redirectUrl = $appBase . $detectedLanguageID . str_replace('/' . $dataID, '/' . $matches->{$languageID}, $removedAppBaseUrl);
                if (isset($this->Logger)) {
                    $this->Logger->write(
                        $this->logChannel, 
                        0, 
                        'Replacing content ID.',
                        [
                            'redirectUrl' => $redirectUrl,
                            'currentUrl' => $currentUrl,
                            'defaultLanguage' => $defaultLanguage,
                            'languageID' => $languageID,
                            'appBase' => $appBase,
                            'removedAppBaseURL' => $removedAppBaseUrl,
                            'originalPostId' => $dataID,
                            'replaceToPostId' => $matches->{$languageID},
                            'replacedRedirectUrl' => $redirectUrl,
                        ]
                    );
                }
                return $redirectUrl;
            }// endif; $matches

            unset($matches);
        }

        unset($result);
    }// getTranslationMatchesForContents


    /**
     * Lookup URL aliases.
     * 
     * @param string $language
     * @param string $alias_url
     * @return array|null Return array with keys if found:
     *                  `content_id` The content ID,<br>
     *                  `content_type` The content type,<br>
     *                  `tm_table` The translation matcher table to look up matched.<br>
     */
    protected function lookupURLAliases($language, $alias_url)
    {
        $URLAliasesDb = new \Rdb\Modules\RdbCMSA\Models\UrlAliasesDb($this->Container);
        $result = $URLAliasesDb->get([
            'language' => $language,
            'alias_url_encoded' => $alias_url,
        ]);
        unset($URLAliasesDb);

        if (is_object($result) && isset($result->alias_content_id) && isset($result->alias_content_type)) {
            if (in_array(strtolower($result->alias_content_type), ['article', 'page'])) {
                $tm_table = 'posts';
            } elseif (in_array(strtolower($result->alias_content_type), ['category', 'tag'])) {
                $tm_table = 'taxonomy_term_data';
            }

            if (isset($tm_table)) {
                return [
                    'content_id' => (int) $result->alias_content_id,
                    'content_type' => $result->alias_content_type,
                    'tm_table' => $tm_table,
                ];
            }
        }
    }// lookupURLAliases


}
