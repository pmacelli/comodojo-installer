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

class ExtenderPlugin extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing (extender) plugins from package ".$package_name."</info>");

        $this->processPlugin($io, 'install', $package_name, $package_extra);

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating (extender) plugins from package ".$package_name."</info>");

        $this->processPlugin($io, 'uninstall', $package_name, $initial_extra);

        $this->processPlugin($io, 'install', $package_name, $target_extra);

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing (extender) plugins from package ".$package_name."</info>");

        $this->processPlugin($io, 'uninstall', $package_name, $package_extra);

    }

    private static function processPlugin($io, $action, $package_name, $package_extra) {

        foreach ($package_extra as $plugin => $configuration) {

            try {

                if ( !self::validatePlugin($configuration) ) throw new InstallerException('Skipping invalid plugin in '.$package_name);

                $plugin_name = $package_name.'-extender-'.$plugin;

                $event = $configuration["event"];

                $class = $configuration["class"];

                $method = empty($configuration["method"]) ? null : $configuration["method"];

                switch ($action) {

                    case 'install':

                        $this->getPackageInstaller()->plugins()->add($package_name, 'extender', $plugin_name, $event, $class, $method);

                        $io->write(" <info>+</info> enabled plugin ".$plugin_name." on event ".$event);

                        break;

                    case 'uninstall':

                        $id = $this->getPackageInstaller()->plugins()->byName($plugin_name)->getId();

                        $this->getPackageInstaller()->plugins()->delete($id);

                        $io->write(" <comment>-</comment> disabled plugin ".$plugin_name." on event ".$event);

                        break;

                }

            } catch (Exception $e) {

                $io->write('<error>Error processing plugin: '.$e->getMessage().'</error>');

            }

        }

    }

    private static function validatePlugin($plugin) {

        return !( empty($plugin["class"]) || empty($plugin["event"]) );

    }

}
