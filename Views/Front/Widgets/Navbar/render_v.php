<!-- navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="<?php echo esc_d__('rdbcmsf', 'Toggle navigation'); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav mr-auto my-2 my-lg-0 navbar-nav-scroll" style="max-height: 100px;">
                        <li class="nav-item<?php if (isset($urls['currentUrl']) && $urls['currentUrl'] === $urls['home']) {echo ' active';} ?>">
                            <a class="nav-link" href="<?php echo $urls['home']; ?>"><?php echo esc_d__('rdbcmsf', 'Home'); ?></a>
                        </li>
                        <?php
                        echo renderNestedList($categories, $Url, ($urls['currentUrl'] ?? ''));
                        unset($categories);
                        ?> 
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo d__('rdbcmsf', 'Languages'); ?> 
                            </a>
                            <ul class="dropdown-menu language-switcher">
                                <?php
                                if (isset($languages['languages']) && is_array($languages['languages'])) {
                                    foreach ($languages['languages'] as $key => $language) {
                                        echo '<li class="nav-item dropdown-item' . (isset($languages['currentLanguage']) && $languages['currentLanguage'] === $key ? ' active' : '') . '">' . PHP_EOL;
                                        echo '<a class="nav-link language-switch-link" href="#" data-rundizbones-languages="' . htmlspecialchars($key, ENT_QUOTES) . '">';
                                        echo ($language['languageName'] ?? $key);
                                        echo '</a>' . PHP_EOL;
                                        echo '</li>' . PHP_EOL;
                                    }// endforeach;
                                    unset($key, $language);
                                }
                                unset($languages);
                                ?> 
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
<!-- /navbar -->


<?php
echo $Assets->renderAssets('js');