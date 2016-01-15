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

class ComodojoSetting extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing setting from package ".$package_name."</info>");

        self::processSetting($io, 'install', $package_name, $package_extra);

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating setting from package ".$package_name."</info>");

        self::processSetting($io, 'uninstall', $package_name, $package_extra);

        self::processSetting($io, 'install', $package_name, $package_extra);

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing setting from package ".$package_name."</info>");

        self::processSetting($io, 'uninstall', $package_name, $package_extra);

    }

    private static function processSetting($io, $action, $package_name, $package_extra) {

        foreach ($package_extra as $setting => $value) {

            try {

                if ( !self::validateSetting($value) ) throw new InstallerException('Skipping invalid setting '.$setting.' in '.$package_name);

                switch ($action) {

                    case 'install':

                        ComodojoConfiguration::addSetting($setting, $value);

                        $io->write(" <info>+</info> added setting ".$setting);

                        break;

                    case 'uninstall':

                        ComodojoConfiguration::removeSetting($setting, $value);

                        $io->write(" <comment>-</comment> removed setting ".$setting);

                        break;

                }

            } catch (Exception $e) {

                $io->write('<error>Error processing setting: '.$e->getMessage().'</error>');

            }

        }

    }

    private static function validateSetting($value) {

        return is_scalar($value);

    }

}
