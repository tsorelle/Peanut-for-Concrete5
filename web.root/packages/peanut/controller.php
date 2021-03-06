<?php 
namespace Concrete\Package\Peanut;

use Concrete\Core\Asset\AssetList;
use Package;
use PageTheme;
use Peanut\sys\ViewModelManager;

// Cloneamental modified for Peanut - Terry SoRelle 2018-12-14
class Controller extends Package
{

	protected $pkgHandle = 'peanut';
	protected $appVersionRequired = '5.7.5.2';
	protected $pkgVersion = '0.9.3';

	public function getPackageDescription()
	{
		return t('The default Elemental theme modified for Peanut.');
	}

	public function getPackageName()
	{
		return t('Peanut');
	}


    public function on_start()
    {
        // add bootstrap modals, not included in C5
        $al = AssetList::getInstance();
        $al->register(
            'javascript', 'bootstrap/modals',
            'js/bootstrap/modals.js',
            array('minify' => false, 'position' =>  \Concrete\Core\Asset\Asset::ASSET_POSITION_FOOTER),
            $this
        );
    }

	public function install()
	{
		$pkg = parent::install();
		PageTheme::add('peanut', $pkg);
	}

}