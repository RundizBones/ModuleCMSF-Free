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
                        <?php echo d__('rdbcmsf', 'This is the home page of CMS front pages module.'); ?><br>
                        <?php printf(d__('rdbcmsf', 'You can start modify the controller of this page at %1$s.'), '<strong>' . $controllerPath . '</strong>'); ?><br>
                        <?php printf(d__('rdbcmsf', 'The views file is located at %1$s.'), '<strong>' . __FILE__ . '</strong>'); ?> 
                    </p>
                    <?php 
                    if (isset($listPosts) && is_array($listPosts)) { 
                        foreach ($listPosts as $eachPost) {
                            if (!empty($eachPost->alias_url_encoded)) {
                                $linkToPost = $Url->getAppBasedPath(true) . '/' . $eachPost->alias_url_encoded;
                            } else {
                                $linkToPost = $Url->getAppBasedPath(true) . '/posts/' . rawurlencode($eachPost->post_type) . '/' . $eachPost->post_id;
                            }
                    ?> 
                    <div class="card container-fluid py-3 my-4">
                        <article class="row">
                            <?php if (isset($eachPost->files->urls->thumbnail)) { ?> 
                            <div class="col-sm-2 mb-3 mb-sm-0 text-center">
                                <img class="img-fluid" src="<?php echo $eachPost->files->urls->thumbnail; ?>" alt="">
                            </div>
                            <?php }// endif; $eachPost->files->urls->thumbnail ?> 
                            <div class="col">
                                <h3><a href="<?php echo $linkToPost; ?>"><?php echo $eachPost->post_name; ?></a></h3>
                                <?php
                                if (!empty($eachPost->revision_body_summary)) {
                                    echo '<div class="summary">' . $eachPost->revision_body_summary . '</div>';
                                } else {
                                    echo '<div class="shorten-post-body">' . mb_strimwidth(strip_tags($eachPost->revision_body_value), 0, 100, '&hellip;') . '</div>';
                                }
                                ?> 
                            </div>
                        </article>
                    </div>
                    <?php 
                            unset($linkToPost);
                        }// endforeach;
                        unset($eachPost);
                    }// endif; $listPosts 
                    ?> 

                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link" href="<?php echo $paginations['previous']; ?>">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="<?php echo $paginations['next']; ?>">Next</a></li>
                        </ul>
                    </nav>
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