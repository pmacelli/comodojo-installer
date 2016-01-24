<?php namespace Comodojo\Installer\Actions;

use Composer\Composer;
use Composer\IO\IOInterface;
use Comodojo\Configuration\Installer as PackageInstaller;

/**
 *
 *
 * @package     Comodojo Framework
 * @author      Marco Giovinazzi <marco.giovinazzi@comodojo.org>
 * @author      Marco Castiello <marco.castiello@gmail.com>
 * @license     GPL-3.0+
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

abstract class AbstractAction implements ActionInterface {

    private $composer;

    private $io;
    
    private $path;
    
    private $package_installer;

    public function __construct(Composer $composer, IOInterface $io, $package_path, PackageInstaller $package_installer) {

        $this->composer = $composer;

        $this->io = $io;
        
        $this->path = $package_path;
        
        $this->package_installer = $package_installer;

    }

    abstract public function install($package_name, $package_extra);

    abstract public function update($package_name, $initial_extra, $target_extra);

    abstract public function uninstall($package_name, $package_extra);

    public function getIO() {

        return $this->io;

    }

    public function getComposer() {

        return $this->composer;

    }
    
    public function getPath() {

        return $this->path;

    }
    
    public function getPackageInstaller() {
        
        return $this->package_installer;
        
    }

}
