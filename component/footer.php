<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
                </main>
                <?php 
                Icarus_Aside::$asideLeft->output();
                Icarus_Aside::$asideRight->output();
                ?>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="level">
                <div class="level-start has-text-centered-mobile">
                    <a class="footer-logo is-block has-mb-6" href="<?php Icarus_Util::$options->index(); ?>">
                    <?php if (Icarus_Config::tryGet('logo_img', $logo_img)): ?>
                        <img src="<?php echo Icarus_Assets::getUrlForAssets($logo_img); ?>" alt="<?php Icarus_Util::$options->title(); ?>" height="28">
                    <?php else: ?>
                        <?php echo Icarus_Config::get('logo_text', Icarus_Util::$options->title); ?>
                    <?php endif; ?>
                    </a>
                    <p class="is-size-7">
                    &copy; <?php 
                    $installYear = Icarus_Config::getSiteInstallYear();
                    $curYear = date('Y');
                    if ($installYear != $curYear)
                        echo $installYear, '&nbsp;-&nbsp;';
                    echo $curYear;
                    ?> <?php echo Icarus_Config::get('profile_author', Icarus_Util::$options->title); ?>&nbsp;
                    Powered by <a href="http://typecho.org/" target="_blank" ref="nofollow noreferrer noopener">Typecho</a> & <a
                            href="https://github.com/KeNorizon/typecho-theme-icarus">Icarus</a>
                    </p>
                </div>
                <div class="level-end">
                <?php $footerLinks = ICarus_Page::getFooterLinks(); 
                if (!empty($footerLinks)): ?>
                    <div class="field has-addons is-flex-center-mobile has-mt-5-mobile is-flex-wrap is-flex-middle">
                    <?php foreach ($footerLinks as $linkItem): ?>
                    <p class="control">
                        <a class="button is-white <?php if (!empty($linkItem[1])) echo 'is-large'; ?>" rel="noopener noreferrer" target="_blank" title="<?php echo $linkItem[0]; ?>" href="<?php echo $linkItem[2]; ?>">
                            <?php if (empty($linkItem[1])):
                                echo $linkItem[0];
                            else: foreach ($linkItem[1] as $iconItem): ?>
                            <i class="fab <?php echo $iconItem; ?>"></i>
                            <?php endforeach; endif; ?>
                        </a>
                    </p>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>
<?php
// jQuery
Icarus_Assets::cdn('js', 'jquery', '3.3.1', 'dist/jquery.min.js');

// Moment.js
Icarus_Assets::cdn('js', 'moment', '2.22.2', 'min/moment-with-locales.min.js');
echo '<script>moment.locale("', str_replace('_', '-', Icarus_Util::$options->lang), '");</script>', PHP_EOL;

// Plugins


// Theme Script
Icarus_Assets::printThemeJs('main.js');
?>
</body>
</html>