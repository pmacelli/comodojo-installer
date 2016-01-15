<?php namespace Comodojo\Installer\Actions;

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

class DispatcherService extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing (dispatcher) services from package ".$package_name."</info>");

        self::processService($io, 'install', $package_name, $package_extra);

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating (dispatcher) services from package ".$package_name."</info>");

        self::processService($io, 'uninstall', $package_name, $package_extra);

        self::processService($io, 'install', $package_name, $package_extra);

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing (dispatcher) services from package ".$package_name."</info>");

        self::processService($io, 'uninstall', $package_name, $package_extra);

    }

    private static function processService($io, $action, $package_name, $package_extra) {

        foreach ($package_extra as $service) {

            try {

                if ( !self::validateService($service) ) throw new InstallerException('Skipping invalid service in '.$package_name);

                switch ($action) {

                    case 'install':

                        DispatcherConfiguration::addRoute($service);

                        $io->write(" <info>+</info> enabled service ".$service["name"]." (".$service["type"].")");

                        break;

                    case 'uninstall':

                        DispatcherConfiguration::removeRoute($service);

                        $io->write(" <comment>-</comment> disabled service ".$service["name"]." (".$service["type"].")");

                        break;

                }

            } catch (Exception $e) {

                $io->write('<error>Error processing service: '.$e->getMessage().'</error>');

            }

        }

    }

    private static function validateService($service) {

        return !( empty($service["name"]) || empty($service["path"]) || empty($service["type"]) || empty($service["target"]) );

    }

}
