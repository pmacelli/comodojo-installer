<?php namespace Comodojo\Installer\Actions;

use \Comodojo\Installer\Components\Filesystem;
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

class Theme extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing themes from package ".$package_name."</info>");

        $this->processTheme($io, 'install', $package_name, $package_extra);

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating themes from package ".$package_name."</info>");

        $this->processTheme($io, 'uninstall', $package_name, $package_extra);

        $this->processTheme($io, 'install', $package_name, $package_extra);

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing themes from package ".$package_name."</info>");

        $this->processTheme($io, 'uninstall', $package_name, $package_extra);

    }

    private function processTheme($io, $action, $package_name, $package_extra) {

        foreach ($package_extra as $theme => $configuration) {

            try {

                if ( !self::validateTheme($configuration) ) throw new InstallerException('Skipping invalid theme in '.$package_name);

                $assets = $configuration['assets'];
                
                $description = empty($configuration['description']) ? null : $configuration['description'];
                
                $fs = new Filesystem();
                
                $path = $this->getPath();

                switch ($action) {

                    case 'install':

                        $fs->rcopy($path.'/'.$assets, COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_THEME_ASSETS.'/'.$theme);
                        
                        $this->getPackageInstaller()->themes()->add($package_name, $theme, $description);

                        $io->write(" <info>+</info> added theme ".$theme);

                        break;

                    case 'uninstall':

                        $id = $this->getPackageInstaller()->themes()->getByName($name)->getId();

                        $this->getPackageInstaller()->themes()->delete($id);
                        
                        $fs->rmdir(COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_THEME_ASSETS.'/'.$theme);

                        $io->write(" <comment>-</comment> removed theme ".$theme);

                        break;

                }

            } catch (Exception $e) {

                $io->write('<error>Error processing theme: '.$e->getMessage().'</error>');

            }

        }

    }

    private static function validateTheme($theme) {

        return !( empty($theme['assets']) );

    }

}
