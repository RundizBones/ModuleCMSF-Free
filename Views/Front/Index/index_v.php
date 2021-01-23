<?php
/* @var $Assets \Rdb\Modules\RdbAdmin\Libraries\Assets */
/* @var $Modules \Rdb\System\Modules */
/* @var $Views \Rdb\System\Views */
/* @var $Url \Rdb\System\Libraries\Url */

require dirname(__DIR__) . '/common/htmlHead_v.php';
?>

        <div class="container">
<?php require dirname(__DIR__) . '/common/pageHeader_v.php'; ?> 
            <div class="row">
                <div class="col-sm-9">
                    <h1 class="mt-4"><?php echo d__('rdbcmsf', 'Welcome'); ?></h1>
                    <p>
                        <?php echo d__('rdbcmsf', 'This is the home page of CMS front pages module.'); ?> 
                        <?php printf(d__('rdbcmsf', 'You can start modify the controller of this page at %1$s.'), '<strong>' . $controllerPath . '</strong>'); ?> 
                    </p>
                </div><!-- .col -->
                <div class="col">
                    <ul class="mt-4">
                        <?php
                        if (isset($listPages) && is_array($listPages)) {
                            foreach ($listPages as $page) {
                                if (!empty($page->alias_url_encoded)) {
                                    $linkToPage = $Url->getAppBasedPath(true) . '/' . $page->alias_url_encoded;
                                } else {
                                    $linkToPage = $Url->getAppBasedPath(true) . '/posts/' . $page->post_type . '/' . $page->post_id;
                                }
                                echo '<li>';
                                echo '<a href="' . $linkToPage . '">';
                                echo $page->post_name;
                                echo '</a>';
                                echo '</li>' . PHP_EOL;
                                unset($linkToPage);
                            }// endforeach;
                            unset($page);
                        }
                        ?> 
                    </ul>
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->

<?php
require dirname(__DIR__) . '/common/htmlFoot_v.php';