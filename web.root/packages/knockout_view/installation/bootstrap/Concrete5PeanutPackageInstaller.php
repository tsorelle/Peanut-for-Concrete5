<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/18/2017
 * Time: 9:42 AM
 */

namespace Tops\concrete5;
use Peanut\sys\DefaultPeanutInstaller;
use Punic\Exception;
use Tops\sys\TConfiguration;
use Tops\sys\TIniFileMerge;
use Tops\sys\TPath;
use Tops\sys\TSession;
use ZipArchive;

class Concrete5PeanutPackageInstaller
{
    private $fileRoot;
    private $packageRoot;
    private $configPath;
    private $templatePath;

    public function __construct()
    {
        $this->fileRoot =  DIR_BASE;
        $this->packageRoot = realpath(__DIR__.'/../..');
        $this->configPath = DIR_APPLICATION.'/config';
        $this->templatePath = realpath(__DIR__.'/..');
        $this->initialize();
    }

    private function initialize() {
        // Assume that if TPath already autoloaded, bootstrap tasks were already run
        if (!class_exists('\Tops\sys\TPath')) {
            $srcRoot = "$this->packageRoot/src";
            // $loader = BootstrapAutoloader::getInstance();
            $autoloaderPath = realpath($this->packageRoot.'/src/tops/sys/Autoloader.php');
            if ($autoloaderPath === false) {
                throw new \Exception('Autoloader path not found.');
            }
            $loader = require($autoloaderPath);
            // $loader->addPsr4('Tops', "$srcRoot/tops");
            $loader->addPsr4('Peanut', "$srcRoot/peanut");
            $loader->addPsr4('Tops\concrete5', "$srcRoot/concrete5");
            $loader->addPsr4('PeanutTest', "$srcRoot/test");

            if (!class_exists('\Tops\sys\TPath')) {
                throw new \Exception('Autoload failed in installer.');
            }

            TPath::Initialize($this->fileRoot);
            TSession::Initialize();
        }
    }

    public function installFiles() {
        $zipfile =  realpath(__DIR__.'/../application.zip');
        $target = $this->fileRoot.'/';
        $zip = new ZipArchive;
        if ($zip->open($zipfile) === TRUE) {
            $zip->extractTo($target);
            $zip->close();
        } else {
            throw new \Exception('Extraction error. Problem with application.zip.');
        }

        $iniPath = realpath("$this->templatePath/ini");
        if ($iniPath === false) {
            throw new \Exception('Cannot locate ini files.');
        }
        $configs = scandir($iniPath);
        foreach ($configs as $file) {
            if (strpos($file,'.ini') !== false) {
                TIniFileMerge::merge(
                    "$iniPath/$file",
                    "$this->configPath/$file");
            }
        }
        TConfiguration::clearCache();
    }

    public static function install() {
        $instance = new Concrete5PeanutPackageInstaller();
        $instance->installFiles();
        $installer = new Concrete5PeanutInstaller();
        $installer->installPackage('peanut');
    }

    public static function uninstall() {
        $installer = new Concrete5PeanutInstaller();
        $installer->uninstallPeanut();
    }

}