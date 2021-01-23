<?php
/* @var $Assets \Rdb\Modules\RdbAdmin\Libraries\Assets */
/* @var $Modules \Rdb\System\Modules */
/* @var $Views \Rdb\System\Views */
/* @var $Url \Rdb\System\Libraries\Url */
?>
<!DOCTYPE html>
<html class="<?php echo ($pageHtmlClasses ?? ''); ?>" lang="<?php echo ($_SERVER['RUNDIZBONES_LANGUAGE'] ?? 'th'); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php echo $Assets->renderAssets('css'); ?> 

        <title><?php
        if (isset($pageHtmlTitle) && is_scalar($pageHtmlTitle)) {
            echo htmlspecialchars($pageHtmlTitle, ENT_QUOTES);
        }
        ?></title>
    </head>
    <body>
        <div class="container">
            <header class="row">
                <div class="col">
                    <h1 class="display-4"><?php echo ($configDb['rdbadmin_SiteName'] ?? 'RundizBones CMS front pages module'); ?></h1>
                </div>
            </header>
        </div>

        <?php echo $Assets->renderAssets('js'); ?> 
    </body>
</html>