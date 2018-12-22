<?php

namespace Concrete\Package\KnockoutView;

use Concrete\Core\Asset\AssetList;
use Package;
use BlockType;
use SinglePage;
use Route;
use Database;
use Tops\concrete5\Concrete5PeanutInstaller;
use Tops\concrete5\Concrete5PeanutPackageInstaller;
use Tops\sys\TConfiguration;

class Controller extends Package
{
    protected $pkgAutoloaderMapCoreExtensions = true; // do not automap src files. Leave it to Peanut
    protected $pkgHandle = 'knockout_view';
    protected $appVersionRequired = '5.8.0';
    protected $pkgVersion = '1.1';

    public function getPackageName()
    {
        return t('Knockout View');
    }

    public function getPackageDescription()
    {
        return t('Support for Knockout and Typescript');
    }

    private $optimize;
    private function getOptimizationSetting() {
        if (!isset($this->optimize)) {
            $this->optimize = false;
            $iniFile = DIR_APPLICATION.'config/settings.ini';
            $settings = @parse_ini_file($iniFile,true);
            $this->optimize = $settings === false ? false : (!empty($settings['']['optimize']));
        }
    }

    public function on_start()
    {
        $al = AssetList::getInstance();
        $al->register(
            'javascript', 'knockoutjs',
            'https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.1/knockout-min.js',
            // 'js/ko/knockout-3.4.1.js',
            array('local' => false,'minify' => false, 'position' =>  \Concrete\Core\Asset\Asset::ASSET_POSITION_HEADER)
        );

        $al->register(
            'javascript', 'headjs',
            'http://cdnjs.cloudflare.com/ajax/libs/headjs/1.0.3/head.load.min.js',
            // 'js/ko/knockout-3.4.1.js',
            array('local' => false,'minify' => false, 'position' =>  \Concrete\Core\Asset\Asset::ASSET_POSITION_HEADER)
        );

        $optimize = $this->getOptimizationSetting();
        $loaderScript = $optimize ?  'dist/loader.min.js' : 'core/PeanutLoader.js' ;
        $al->register(
            'javascript', 'peanut',
            'pnut/'.$loaderScript,
            array('minify' => false, 'position' =>  \Concrete\Core\Asset\Asset::ASSET_POSITION_HEADER),
            $this
        );
    }


    public function install()
    {
        // todo: use package installer - deferred
        /*
        $installationIni = @parse_ini_file(__DIR__.'/installation/installation.ini');
        if ($installationIni !== false && !empty($installationIni['enabled'])) {
            require_once (__DIR__.'/installation/bootstrap/Concrete5PeanutPackageInstaller.php');
            Concrete5PeanutPackageInstaller::install();
        }
        */
        $pkg = parent::install();
        BlockType::installBlockType('knockout_view', $pkg);
        return $pkg;
    }

    public function uninstall()
    {
        parent::uninstall();
        $db = Database::connection();
        $db->executeQuery('DROP TABLE IF EXISTS btKnockoutView');
        // todo: get package installer working - deferred
        /*
                $installationIni = @parse_ini_file(__DIR__.'/installation/installation.ini');
                if ($installationIni !== false && (!empty($installationIni['enabled'])) && class_exists('\Tops\concrete5\Concrete5PeanutInstaller')) {
                    $installer = new \Tops\concrete5\Concrete5PeanutInstaller();
                    $installer->uninstallAll();
                }
            */
    }
}