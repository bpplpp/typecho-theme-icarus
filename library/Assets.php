<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
class Icarus_Assets
{
    private static $_assetsBaseUrl;
    private static $_cdnProviders = array(
        'assets' => array(
            'jsdelivr' => array(
                'bulma' => 'https://cdn.jsdelivr.net/npm/bulma@{version}/{file}',
            ),
        ),
        'font' => array(
            'google' => array(
                'font' => 'https://fonts.googleapis.com/{type}?family={fontname}'
            )
        ),
        'icon' => array(
            'fontawesome' => array(
                'icon' => 'https://use.fontawesome.com/releases/v5.4.1/css/all.css'
            ),
            'jsdelivr' => array(
                'icon' => 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.4.1/css/all.min.css'
            ),
        ),
    );
    private static $_assetsCdnUrl = array();
    public static function init()
    {
        self::$_assetsBaseUrl = Icarus_Config::get(
            'assets_baseUrl',
            Typecho_Common::url('assets', Icarus_Util::$options->themeUrl)
        );  

        // dummy
        self::loadAssetsCDNConfig('jsdelivr', 'assets');
        self::loadAssetsCDNConfig('google', 'font');
        self::loadAssetsCDNConfig('fontawesome', 'icon');


        self::loadAssetsCustomConfig('assets');
        self::loadAssetsCustomConfig('font');
        self::loadAssetsCustomConfig('icon');
    }

    private static function loadAssetsCDNConfig($cdnName, $type)
    {
        if (!array_key_exists($type, self::$_cdnProviders))
            return;

        $cdn = self::$_cdnProviders[$type];

        if (!array_key_exists($cdnName, $cdn))
            return;

        self::$_assetsCdnUrl = array_merge(self::$_assetsCdnUrl, $cdn[$cdnName]);
    }

    private static function loadAssetsCustomConfig($type)
    {

    }

    public static function getUrlForAssets($path)
    {
        if (Icarus_Util::isUrl($path))
            return $path;
        return Typecho_Common::url($path, self::$_assetsBaseUrl);
    }

    public static function printCssTag($cssUrl)
    {
        echo '<link rel="stylesheet" href="', $cssUrl, '" />', PHP_EOL;
    }

    public static function printJsTag($jsUrl, $defer = false)
    {
        if ($defer)
            echo '<script src="', $jsUrl, '"></script>', PHP_EOL;
        else
            echo '<script src="', $jsUrl, '" defer></script>', PHP_EOL;
    }

    public static function printThemeCss($name)
    {
        self::printCssTag(self::getUrlForAssets("css/" . $name));
    }

    public static function printThemeJs($name, $defer = false)
    {
        self::printJsTag(self::getUrlForAssets("js/" . $name), $defer);
    }

    public static function getCdnUrl($name, $version, $file)
    {
        if (!array_key_exists($name, self::$_assetsCdnUrl))
            return;
        $cdnUrl = self::$_assetsCdnUrl[$name];
        return str_replace(array('{version}', '{file}'), array($version, $file), $cdnUrl);
    }

    public static function getFontCdnUrl($fontname, $type = 'css')
    {
        if (!array_key_exists('font', self::$_assetsCdnUrl))
            return;
        $cdnUrl = self::$_assetsCdnUrl['font'];
        return str_replace(array('{fontname}', '{type}'), array($fontname, $type), $cdnUrl);
    }

    public static function getIconCdnUrl()
    {
        if (!array_key_exists('icon', self::$_assetsCdnUrl))
            return;
        return self::$_assetsCdnUrl['icon'];
    }

    public static function cdn($cssJs, $type)
    {
        $args = func_get_args();
        
        $defer = $cssJs == 'jsDefer';
        if ($defer)
            $cssJs = 'js';
        
        $funcName = '';
        switch ($type)
        {
            case 'font':
                array_splice($args, 0, 2);
                $funcName = 'getFontCdnUrl';
                if (count($args) == 1)
                {
                    array_push($args, $cssJs);
                }
                break;
            case 'icon':
                array_splice($args, 0, 2);
                $funcName = 'getIconCdnUrl';
                break;
            default:
                array_splice($args, 0, 1);
                $funcName = 'getCdnUrl';
                break;
        }
        $url = call_user_func_array(array('Icarus_Assets', $funcName), $args);
        if (empty($url))
        {
            return;
        }
        if ($cssJs == 'js') // js tag
        {
            self::printJsTag($url, $defer);
        }
        else // css tag
        {
            self::printCssTag($url);
        }
    }

    public static function config($form)
    {

    }
}