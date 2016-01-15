<?php namespace Comodojo\Installer\Actions;

use \Comodojo\Installer\Configuration\ComodojoConfiguration;
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

class ComodojoRpc extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing rpc services from package ".$package_name."</info>");

        self::processRpc($io, 'install', $package_name, $package_extra);

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating rpc services from package ".$package_name."</info>");

        self::processRpc($io, 'uninstall', $package_name, $package_extra);

        self::processRpc($io, 'install', $package_name, $package_extra);

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing rpc services from package ".$package_name."</info>");

        self::processRpc($io, 'uninstall', $package_name, $package_extra);

    }

    private static function processRpc($io, $action, $package_name, $package_extra) {

        foreach ($package_extra as $rpc) {

            try {

                if ( !self::validateRpc($rpc) ) throw new InstallerException('Skipping invalid rpc service in '.$package_name);

                switch ($action) {

                    case 'install':

                        ComodojoConfiguration::addRpc($package_name, $rpc);

                        $io->write(" <info>+</info> added rpc service ".$rpc['name']);

                        break;

                    case 'uninstall':

                        ComodojoConfiguration::removeRpc($package_name, $rpc);

                        $io->write(" <comment>-</comment> removed rpc service ".$rpc['name']);

                        break;

                }

            } catch (Exception $e) {

                $io->write('<error>Error processing rpc service: '.$e->getMessage().'</error>');

            }

        }

    }

    private static function validateRpc($rpc) {

        return !( empty($rpc['name']) || empty($rpc['callback']) || ( isset($rpc['signatures']) && !is_array($rpc['signatures']) ) );

    }

}
