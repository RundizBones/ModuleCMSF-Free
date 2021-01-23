<?php
/* @var $Assets \Rdb\Modules\RdbAdmin\Libraries\Assets */
/* @var $Modules \Rdb\System\Modules */
/* @var $Views \Rdb\System\Views */
/* @var $Url \Rdb\System\Libraries\Url */

require dirname(__DIR__, 2) . '/common/htmlHead_v.php';
?>

        <div class="container">
<?php require dirname(__DIR__, 2) . '/common/pageHeader_v.php'; ?> 
            <div class="row">
                <div class="col">
                    <article>
                        <header class="mt-4">
                            <?php if (isset($Post->files->urls->original)) { ?> 
                            <div class="post-feature-image">
                                <img class="img-fluid" src="<?php echo $Post->files->urls->original; ?>" alt="">
                            </div>
                            <?php }// endif; image ?> 
                            <h1><?php echo $Post->post_name; ?></h1>
                        </header>
                        <section class="post-author mb-2">
                            <?php printf(d__('rdbcmsf', 'By %1$s'), $Post->user_display_name); ?> 
                            <time><?php echo $Post->post_publish_date; ?></time>
                        </section>
                        <div class="post-contents">
                            <?php echo $Post->revision_body_value; ?> 
                        </div>
                    </article>

                    <section class="container-fluid taxonomy-data">
                        <div class="row">
                            <div class="col">
                                <?php
                                if (isset($Post->categories) && is_array($Post->categories) && !empty($Post->categories)) {
                                    echo dn__('rdbcmsf', 'Category', 'Categories', count($Post->categories)) . ': ';
                                    foreach ($Post->categories as $category) {
                                        $linkCategory = $Url->getAppBasedPath(true) . '/taxonomies/' . $category->t_type . '/' . $category->tid;
                                        echo '<a href="' . $linkCategory . '">';
                                        echo $category->t_name;
                                        echo '</a> ';
                                        unset($linkCategory);
                                    }// endforeach;
                                    unset($category);
                                }
                                ?> 
                            </div>
                            <div class="col">
                                <?php
                                if (isset($Post->tags) && is_array($Post->tags) && !empty($Post->tags)) {
                                    echo dn__('rdbcmsf', 'Tag', 'Tags', count($Post->tags)) . ': ';
                                    foreach ($Post->tags as $tag) {
                                        $linkTag = $Url->getAppBasedPath(true) . '/taxonomies/' . $tag->t_type . '/' . $tag->tid;
                                        echo '<a href="' . $linkTag . '">';
                                        echo $tag->t_name;
                                        echo '</a> ';
                                        unset($linkTag);
                                    }// endforeach;
                                    unset($tag);
                                }
                                ?> 
                            </div>
                        </div>
                    </section>
                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->

<?php
require dirname(__DIR__, 2) . '/common/htmlFoot_v.php';