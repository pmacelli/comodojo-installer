<?php namespace Comodojo\Installer\Actions;

use \Comodojo\Exception\InstallerException;
use \Exception;

/**
 * Comodojo Installer
 *
 * @package     Comodojo Framework
 * @author      Marco Giovinazzi <marco.giovinazzi@comodojo.org>
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

class Package extends AbstractAction implements PackageActionInterface {

    public function install($package_name, $package_version) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing package $package_name</info>");

        $bundle = array(
            'id' => 0,
            'package' => $package_name,
            'version' => $package_version
        );

        return $this->getPackageInstaller()->packages()->add($bundle);

    }

    public function update($initial_package_name, $initial_package_version, $target_package_name, $target_package_version) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating package $package_name</info>");

        $current_package = $this->getPackageInstaller()->packages()->get($initial_package_name);

        $id = $current_package->get('id');
        $version = $current_package->get('version');

        if ( $version != $initial_package_version ) {
            throw new InstallerException("Cannot update package: version of $initial_package_name mismatch ($version != $initial_package_version)");
        }

        $io->write("<info>>>> Package id: $id</info>");

        $bundle = array(
            'id' => $id,
            'package' => $target_package_name,
            'version' => $target_package_version
        );

        return $this->getPackageInstaller()->packages()->update($bundle);

    }

    public function uninstall($package_name, $package_version) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing package $package_name</info>");

        $current_package = $this->getPackageInstaller()->packages()->get($package_name);

        $id = $current_package->get('id');
        $version = $current_package->get('version');

        if ( $version != $package_version ) {
            throw new InstallerException("Cannot remove package: version of $package_name mismatch ($version != $package_version)");
        }

        return $this->getPackageInstaller()->packages()->delete($id);

    }

}
