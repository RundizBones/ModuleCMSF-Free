            <header class="row">
                <div class="col">
                    <h1 class="display-4"><?php echo ($configDb['rdbadmin_SiteName'] ?? 'RundizBones CMS front pages module'); ?></h1>
                </div>
            </header>
<?php echo $Modules->execute('Rdb\\Modules\\RdbCMSF\\Controllers\\Front\\Widgets\\Navbar:render'); ?> 