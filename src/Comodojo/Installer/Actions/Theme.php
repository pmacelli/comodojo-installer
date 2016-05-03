<?php namespace Comodojo\Installer\Actions;

use \Comodojo\Installer\Components\ArrayOps;
use \Comodojo\Exception\InstallerException;
use \Exception;

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

class Theme extends AbstractAction implements ActionInterface {

    public function install($package_id, $package_extra) {

        $io = $this->getIO();

        foreach ($package_extra as $theme => $config) {

            $io->write("+ Installing theme $theme...");

            $bundle = array_replace(
                array(
                    "description" => null
                ),
                $config
            );

            $bundle['id'] = 0;
            $bundle['package'] = $package_id;

            $result = $this->getPackageInstaller()->themes()->add($bundle);

            $io->write("<info>done.</info>", false);

        }

    }

    public function update($package_id, $initial_extra, $target_extra) {

        $io = $this->getIO();

        list($uninstall, $update, $install) = ArrayOps::arrayCircularDiffKey($initial_extra, $target_extra);

        if ( !empty($uninstall) ) {
            $this->uninstall($package_id, ArrayOps::arrayFilterByKey($uninstall, $initial_extra));
        }

        if ( !empty($install) ) {
            $this->install($package_id, ArrayOps::arrayFilterByKey($install, $target_extra));
        }

        foreach ( ArrayOps::arrayFilterByKey($update, $target_extra) as $name => $config ) {

            $io->write("~ Updating theme $name...");

            $this->getPackageInstaller()->themes()->get($name)->merge($config)->persist();

            $io->write("<info>done.</info>", false);

        }

    }

    public function uninstall($package_id, $package_extra) {

        $io = $this->getIO();

        foreach ($package_extra as $theme => $config) {

            $io->write("- Removing theme $theme...");

            $this->getPackageInstaller()->tasks()->get($theme)->delete();

            $io->write("<info>done.</info>", false);

        }

    }

}
