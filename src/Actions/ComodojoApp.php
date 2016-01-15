<?php namespace Comodojo\Installer\Actions;

use \Comodojo\Installer\Configuration\AppConfiguration;
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

class ComodojoApp extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing app ".$package_name."</info>");

        self::processApp($io, 'install', $package_name, $package_extra);

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating app ".$package_name."</info>");

        self::processApp($io, 'uninstall', $package_name, $package_extra);

        self::processApp($io, 'install', $package_name, $package_extra);

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing app ".$package_name."</info>");

        self::processApp($io, 'uninstall', $package_name, $package_extra);

    }

    private static function processApp($io, $action, $package_name, $package_extra) {

        foreach ($package_extra as $app) {

            try {

                if ( !self::validateApp($app) ) throw new InstallerException('Skipping invalid app '.$package_name);

                $description = isset($app['description']) ? $app['description'] : null;

                switch ($action) {

                    case 'install':

                        self::copyAssets($app["assets"]);

                        AppConfiguration::registerApp($app['name'], $app['description']);

                        $io->write(" <info>+</info> added app ".$package_name." (".$app['name'].")");

                        break;

                    case 'uninstall':

                        self::deleteAssets($app["assets"]);

                        AppConfiguration::removeApp($app['name'], $app['description']);

                        $io->write(" <comment>-</comment> removed app ".$package_name." (".$app['name'].")");

                        break;

                }

            } catch (Exception $e) {

                $io->write('<error>Error processing app: '.$e->getMessage().'</error>');

            }

        }

    }

    private static function copyAssets($assets) {

    }

    private static function validateApp($app) {

        return !( empty($app["name"]) || empty($app["assets"]) );

    }

}
