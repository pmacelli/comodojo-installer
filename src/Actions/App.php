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

class App extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing apps from ".$package_name."</info>");

        foreach ($package_extra as $app => $configuration) {

            $this->addApp($io, $package_name, $app, $configuration);

        }

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating apps from ".$package_name."</info>");

        $old_apps = array_keys($initial_extra);
        
        $new_apps = array_keys($target_extra);
        
        $uninstall = array_diff($old_apps, $new_apps);

        $install = array_diff($new_apps, $old_apps);

        $update = array_intersect($old_apps, $new_apps);
        
        foreach ( $uninstall as $app ) {
            
            $this->removeApp($io, $package_name, $app, $initial_extra[$app]);
            
        }
        
        foreach ( $install as $app ) {
            
            $this->addApp($io, $package_name, $app, $target_extra[$app]);
            
        }
        
        foreach ( $update as $app ) {
            
            $this->updateApp($io, $package_name, $app, $initial_extra[$app], $target_extra[$app]);
            
        }

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing apps from ".$package_name."</info>");

        foreach ($package_extra as $app => $configuration) {

            $this->removeApp($io, $package_name, $app, $configuration);

        }

    }

    private function addApp($io, $package_name, $app, $configuration) {
        
        $description = empty($configuration['description']) ? null : $configuration['description'];
        
        $path = $this->getPath();

        $fs = new Filesystem();
        
        $assets = empty($configuration['assets']) ? null : $configuration['assets'];

        try {
            
            if ( $assets !== null ) {
            
                $fs->rcopy($path.'/'.$assets, COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_APP_ASSETS.'/'.$app);    
                
            }

            $this->getPackageInstaller()->apps()->add($package_name, $app, $description);
            
            $io->write(" <info>+</info> added app ".$app);

        } catch (Exception $e) {

            $io->write('<error>Error processing app: '.$e->getMessage().'</error>');

        }
        
    }
    
    private function removeApp($io, $package_name, $app, $configuration) {
        
        $fs = new Filesystem();
        
        $assets = empty($configuration['assets']) ? null : $configuration['assets'];
        
        try {

            $id = $this->getPackageInstaller()->apps()->getByName($app)->getId();

            $this->getPackageInstaller()->apps()->delete($id);
            
            if ( $assets !== null ) {

                $fs->rmdir(COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_APP_ASSETS.'/'.$app);
            
            }

            $io->write(" <comment>-</comment> removed app ".$app);

        } catch (Exception $e) {

            $io->write('<error>Error processing app: '.$e->getMessage().'</error>');

        }
        
    }
    
    private function updateApp($io, $package_name, $app, $old_configuration, $new_configuration) {
        
        $description = empty($new_configuration['description']) ? null : $new_configuration['description'];
        
        $path = $this->getPath();

        $fs = new Filesystem();
        
        $old_assets = empty($old_configuration['assets']) ? null : $old_configuration['assets'];
        
        $new_assets = empty($new_configuration['assets']) ? null : $new_configuration['assets'];
        
        try {
            
            $id = $this->getPackageInstaller()->apps()->getByName($app)->getId();
            
            $this->getPackageInstaller()->apps()->update($id, $package_name, $app, $description);

            if ( $old_assets !== null ) {
                
                $fs->rmdir(COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_APP_ASSETS.'/'.$app);
                
            }
            
            if ( $new_assets !== null ) {
                
                $fs->rcopy($path.'/'.$new_assets, COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_APP_ASSETS.'/'.$app);
                
            }

            $io->write(" <comment>~</comment> updated app ".$app);

        } catch (Exception $e) {

            $io->write('<error>Error processing app: '.$e->getMessage().'</error>');

        }
        
    }

}
