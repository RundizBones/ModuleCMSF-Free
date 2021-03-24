<?php
/* @var $Assets \Rdb\Modules\RdbAdmin\Libraries\Assets */
/* @var $Modules \Rdb\System\Modules */
/* @var $Views \Rdb\System\Views */
/* @var $Url \Rdb\System\Libraries\Url */
/* @var $FilesSubController \Rdb\Modules\RdbCMSA\Controllers\Admin\SubControllers\Files\FilesSubController */

require dirname(__DIR__, 2) . '/common/htmlHead_v.php';
?>

        <div class="container">
<?php require dirname(__DIR__, 2) . '/common/pageHeader_v.php'; ?> 
            <div class="row">
                <div class="col">
                    <article>
                        <header class="mt-4">
                            <?php
                            if (isset($File->originalFullURL)) {
                                if (stripos($File->file_mime_type, 'image/') !== false && in_array($File->file_ext, $FilesSubController->imageExtensions)) {
                                    echo '<a href="' . $File->originalFullURL . '">'
                                        . '<img class="img-fluid" src="' . $File->originalFullURL . '" alt="' . htmlspecialchars($File->file_original_name, ENT_QUOTES) . '">'
                                        . '</a>' . PHP_EOL;
                                } elseif (stripos($File->file_mime_type, 'audio/') !== false && in_array($File->file_ext, $FilesSubController->audioExtensions)) {
                                    echo '<audio class="audio-media-views" controls>'
                                        . '<source src="' . $File->originalFullURL . '">'
                                        . '</audio>' . PHP_EOL;
                                } elseif (stripos($File->file_mime_type, 'video/') !== false && in_array($File->file_ext, $FilesSubController->videoExtensions)) {
                                    echo '<div class="embed-responsive embed-responsive-16by9">'
                                        . '<video class="embed-responsive-item" controls>'
                                        . '<source src="' . $File->originalFullURL . '">'
                                        . '</video>'
                                        . '</div>' . PHP_EOL;
                                } else {
                                    echo '<a href="' . $File->originalFullURL . '">'
                                        . $File->file_original_name
                                        . '</a>' . PHP_EOL;
                                }
                            }
                            ?> 
                            <h1><?php echo $File->file_media_name; ?></h1>
                        </header>
                        <section class="post-author mb-2">
                            <?php printf(d__('rdbcmsf', 'By %1$s'), $File->user_display_name); ?> 
                            <time><?php echo $File->file_add; ?></time>
                        </section>
                        <div class="post-contents mb-3">
                            <?php echo $File->file_media_description; ?> 
                        </div>
                    </article>

                </div><!-- .col -->
            </div><!-- .row -->
        </div><!-- .container -->

<?php
require dirname(__DIR__, 2) . '/common/htmlFoot_v.php';