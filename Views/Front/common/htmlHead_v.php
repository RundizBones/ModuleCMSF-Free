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

        <?php
        if (isset($pageHtmlHead)) {
            echo $pageHtmlHead;
        }
        ?> 
    </head>
    <body>