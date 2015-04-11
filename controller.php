<?php

namespace Concrete\Package\EcSfs;

use Concrete\Core\Antispam\Library;
use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected $pkgHandle = 'ec_sfs';
    protected $appVersionRequired = '5.7.4';
    protected $pkgVersion = '0.9.0';

    public function getPackageName()
    {
        return t('Stop Forum Spam');
    }

    public function getPackageDescription()
    {
        return t('Anti-spam provider that checks Stop Forum Spam');
    }

    public function install()
    {
        $pkg = parent::install();
        Library::add('ec_sfs', 'Stop Forum Spam', $pkg);
    }
}