<?php namespace Comodojo\Installer\Actions;

use \Comodojo\Installer\Configuration\ExtenderConfiguration;
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

class ExtenderCommand extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing (extender) commands from package ".$package_name."</info>");

        self::processCommand($io, 'install', $package_name, $package_extra);

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating (extender) commands from package ".$package_name."</info>");

        self::processCommand($io, 'uninstall', $package_name, $package_extra);

        self::processCommand($io, 'install', $package_name, $package_extra);

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing (extender) commands from package ".$package_name."</info>");

        self::processCommand($io, 'uninstall', $package_name, $package_extra);

    }

    private static function processCommand($io, $action, $package_name, $package_extra) {

        foreach ($package_extra as $command => $actions) {

            try {

                if ( !self::validateCommand($actions) ) throw new InstallerException('Skipping invalid command '.$command.' in '.$package_name);

                switch ($action) {

                    case 'install':

                        ExtenderConfiguration::addCommand($package_name, $command, $actions);

                        $io->write(" <info>+</info> enabled command ".$command);

                        break;

                    case 'uninstall':

                        ExtenderConfiguration::removeCommand($package_name, $command);

                        $io->write(" <comment>-</comment> disabled command ".$command);

                        break;

                }

            } catch (Exception $e) {

                $io->write('<error>Error processing command: '.$e->getMessage().'</error>');

            }

        }

    }

    private static function validateCommand($actions) {

        return !empty($actions["class"]);

    }

}
