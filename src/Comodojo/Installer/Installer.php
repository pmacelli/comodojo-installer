<?php namespace Comodojo\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Comodojo\Exception\InstallerException;
use Comodojo\Installer\Components\ArrayOps;
use Comodojo\Installer\Properties\Parser;
use Comodojo\Installer\Actions\Package as PackageManager;
use Comodojo\Installer\Registry\SupportedTypes;
use Comodojo\Configuration\Installer as PackageInstaller;


/**
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

class Installer extends LibraryInstaller {

    protected $package_installer;

    public function __construct(IOInterface $io, Composer $composer, PackageInstaller $package_installer = null) {

        $this->package_installer = $package_installer;

        parent::__construct($io, $composer);

    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType) {

        //return in_array($packageType, SupportedTypes::getTypes());
        return $packageType == 'comodojo-package';

    }

    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {

        parent::install($repo, $package);

        if ( is_null($this->package_installer) ) {

            $this->io->write('<error>PackageInstaller not ready or missing configuration: package could not be installed.</error>');

        } else {

            $this->packageInstall($package);

        }

    }

    /**
     * {@inheritDoc}
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target) {

        parent::update($repo, $initial, $target);

        if ( is_null($this->package_installer) ) {

            $this->io->write('<error>PackageInstaller not ready or missing configuration: package could not be installed.</error>');

        } else {

            $this->packageUpdate($initial, $target);

        }

    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package) {

        if ( is_null($this->package_installer) ) {

            $this->io->write('<error>PackageInstaller not ready or missing configuration: package could not be installed.</error>');

        } else {

            $this->packageUninstall($package);

        }

        parent::uninstall($repo, $package);

    }

    private function packageInstall($package) {

        // get package properties

        $package_name = $package->getPrettyName();

        $package_path = $this->composer->getInstallationManager()->getInstallPath($package);

        $package_version = $package->getPrettyVersion();

        // parse package content

        $actions_map = Parser::parse($package);

        // get local installer

        $installer = $this->getPackageInstaller();

        // init packagemanager and install package

        $package_manager = new PackageManager($this->composer, $this->io, $package_path, $installer);

        $package_id = $package_manager->install($package_name, $package_version);

        // perform actions

        foreach ($actions_map as $action_class => $extra) {

            $action = new $action_class($this->composer, $this->io, $package_path, $installer);

            $action->install($package_id, $extra);

        }

    }

    private function packageUninstall($package) {

        // get package properties

        $package_name = $package->getPrettyName();

        $package_path = $this->composer->getInstallationManager()->getInstallPath($package);

        $package_version = $package->getPrettyVersion();

        // parse package content

        // $actions_map = Parser::parse($package);

        // get local installer

        $installer = $this->getPackageInstaller();

        // init packagemanager and remove package

        $package_manager = new PackageManager($this->composer, $this->io, $package_path, $installer);

        $package_manager->uninstall($package_name, $package_version);

        // perform actions

        // foreach ($actions_map as $action_class => $extra) {
        //
        //     $action = new $action_class($this->composer, $this->io, $package_path, $installer);
        //
        //     $action->install($package_id, $extra);
        //
        // }

    }

    private function packageUpdate($initial, $target) {

        // get initial package properties

        $initial_package_name = $initial->getPrettyName();

        $initial_package_path = $this->composer->getInstallationManager()->getInstallPath($initial);

        $initial_package_version = $initial->getPrettyVersion();

        // get target package properties

        $target_package_name = $target->getPrettyName();

        $target_package_path = $this->composer->getInstallationManager()->getInstallPath($target);

        $target_package_version = $target->getPrettyVersion();

        // get local installer

        $installer = $this->getPackageInstaller();

        // init packagemanager and update package, just in case

        $package_manager = new PackageManager($this->composer, $this->io, $initial_package_path, $installer);

        $package_id = $package_manager->update($initial_package_name, $initial_package_version, $target_package_name, $target_package_version);

        // map actions

        $initial_actions_map = Parser::parse($initial);

        $target_actions_map = Parser::parse($target);

        list($uninstall, $update, $install) = ArrayOps::arrayCircularDiffKey($initial_actions_map, $target_actions_map);

        foreach ($uninstall as $action => $extra) {

            $action_instance = new $action($this->composer, $this->io, $initial_package_path, $installer);

            $action_instance->uninstall($package_id, $extra);

        }

        foreach ($install as $action => $extra) {

            $action_instance = new $action($this->composer, $this->io, $target_package_path, $installer);

            $action_instance->install($package_id, $extra);

        }

        foreach ($update as $action => $extra) {

            $action_instance = new $action($this->composer, $this->io, $target_package_path, $installer);

            $action_instance->update($package_id, $this->initial_actions_map[$action], $extra);

        }

    }

    protected function getPackageInstaller() {

        return $this->package_installer;

    }

}
