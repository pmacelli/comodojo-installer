<?php namespace Comodojo\Installer;

use \Composer\Composer;
use \Composer\IO\IOInterface;
use \Composer\Installer\LibraryInstaller;
use \Composer\Package\PackageInterface;
use \Composer\Repository\InstalledRepositoryInterface;
use \Comodojo\Installer\Components\InstallerConfiguration;
use \Comodojo\Foundation\Base\Configuration;
use \Comodojo\Exception\InstallerException;
use \Comodojo\Installer\Components\InstallerDriverManager;
use \Comodojo\Foundation\Utils\ArrayOps;

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

    protected $supported_drivers;

    protected $drivers = [];

    public function __construct(IOInterface $io, Composer $composer, Configuration $configuration, InstallerConfiguration $installer_configuration) {

        $extra = $installer_configuration->getPackageExtra();

        $this->supported_drivers = array_keys($extra);

        foreach ($extra as $name => $specs) {
            $this->drivers[$name] = new InstallerDriverManager($composer, $io, $configuration, $specs);
        }

        parent::__construct($io, $composer);

    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType) {

        $types = $this->installer_configuration->getPackageTypes();

        return in_array($packageType, $types);

    }

    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package) {

        parent::install($repo, $package);

        $this->packageInstall($package);

    }

    /**
     * {@inheritDoc}
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target) {

        parent::update($repo, $initial, $target);

        $this->packageUpdate($initial, $target);

    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package) {

        $this->packageUninstall($package);

        parent::uninstall($repo, $package);

    }

    private function packageInstall($package) {

        // get package properties

        $package_name = $package->getPrettyName();
        $package_path = $this->composer->getInstallationManager()->getInstallPath($package);
        $package_version = $package->getPrettyVersion();
        $package_extra = $package->getExtra();

        // parse package content
        $supported_fields = ArrayOps::filterByKeys($this->supported_drivers, $package_extra);

        // invoke driver
        foreach ($supported_fields as $name => $config) {

            $repo = $this->drivers[$name];
            $driver = $repo->getDriver();
            $driver->install($package_name, $package_path, $config);

        }

    }

    private function packageUninstall($package) {

        // get package properties

        $package_name = $package->getPrettyName();
        $package_path = $this->composer->getInstallationManager()->getInstallPath($package);
        $package_version = $package->getPrettyVersion();
        $package_extra = $package->getExtra();

        // parse package content
        $supported_fields = ArrayOps::filterByKeys($this->supported_drivers, $package_extra);

        // invoke driver
        foreach ($supported_fields as $name => $config) {

            $repo = $this->drivers[$name];
            $driver = $repo->getDriver();
            $driver->uninstall($package_name, $package_path, $config);

        }

    }

    private function packageUpdate($initial, $target) {

        // get initial package properties

        $initial_package_name = $initial->getPrettyName();
        $initial_package_path = $this->composer->getInstallationManager()->getInstallPath($initial);
        $initial_package_version = $initial->getPrettyVersion();
        $initial_package_extra = $initial->getExtra();

        // get target package properties

        $target_package_name = $target->getPrettyName();
        $target_package_path = $this->composer->getInstallationManager()->getInstallPath($target);
        $target_package_version = $target->getPrettyVersion();
        $target_package_extra = $target->getExtra();

        // parse package content
        $initial_supported_fields = ArrayOps::filterByKeys($this->supported_drivers, $initial_package_extra);
        $target_supported_fields = ArrayOps::filterByKeys($this->supported_drivers, $target_package_extra);

        list($uninstall, $update, $install) = ArrayOps::arrayCircularDiffKeys($initial_supported_fields, $target_supported_fields);

        foreach ($uninstall as $name => $config) {

            $repo = $this->drivers[$name];
            $driver = $repo->getDriver();
            $driver->uninstall($initial_package_name, $initial_package_path, $config);

        }

        foreach ($install as $name => $config) {

            $repo = $this->drivers[$name];
            $driver = $repo->getDriver();
            $driver->uninstall($target_package_name, $target_package_path, $config);

        }

        foreach ($update as $name => $config) {

            $repo = $this->drivers[$name];
            $driver = $repo->getDriver();
            $driver->update($target_package_name, $target_package_path, $initial_supported_fields[$name], $config);

        }

    }

}
