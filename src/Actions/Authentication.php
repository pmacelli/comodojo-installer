<?php namespace Comodojo\Installer\Actions;

use \Comodojo\Exception\InstallerException;
use \Exception;

/**
 *
 *
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

class Authentication extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing authentication providers from ".$package_name."</info>");

        foreach ($package_extra as $provider => $configuration) {

            $this->addAuthProvider($io, $package_name, $provider, $configuration);

        }

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating authentication providers from ".$package_name."</info>");

        $old_providers = array_keys($initial_extra);
        
        $new_providers = array_keys($target_extra);
        
        $uninstall = array_diff($old_providers, $new_providers);

        $install = array_diff($new_providers, $old_providers);

        $update = array_intersect($old_providers, $new_providers);
        
        foreach ( $uninstall as $provider ) {
            
            $this->removeAuthProvider($io, $package_name, $provider, $initial_extra[$provider]);
            
        }
        
        foreach ( $install as $provider ) {
            
            $this->addAuthProvider($io, $package_name, $provider, $target_extra[$provider]);
            
        }
        
        foreach ( $update as $provider ) {
            
            $this->updateAuthProvider($io, $package_name, $provider, $target_extra[$provider]);
            
        }

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing authentication providers from ".$package_name."</info>");

        foreach ($package_extra as $provider => $configuration) {

            $this->removeAuthProvider($io, $package_name, $provider, $configuration);

        }

    }
    
    private function addAuthProvider($io, $package_name, $provider, $configuration) {
        
        try {

            if ( !self::validateProvider($configuration) ) throw new InstallerException('Skipping invalid authentication provider '.$provider.' in '.$package_name);

            $class = $configuration['class'];
            
            $description = empty($configuration['description']) ? null : $configuration['description'];
            
            $this->getPackageInstaller()->authentication()->add($package_name, $provider, $class, $description);

            $io->write(" <info>+</info> added authentication provider ".$provider);

        } catch (Exception $e) {

            $io->write('<error>Error processing authentication provider: '.$e->getMessage().'</error>');

        }
        
    }
    
    private function removeAuthProvider($io, $package_name, $provider, $configuration) {
        
        try {

            if ( !self::validateProvider($configuration) ) throw new InstallerException('Skipping invalid authentication provider '.$provider.' in '.$package_name);
            
            $id = $this->getPackageInstaller()->authentication()->getByName($provider)->getId();

            $this->getPackageInstaller()->authentication()->delete($id);

            $io->write(" <comment>-</comment> removed authentication provider ".$provider);

        } catch (Exception $e) {

            $io->write('<error>Error processing authentication provider: '.$e->getMessage().'</error>');

        }
        
    }
    
    private function updateAuthProvider($io, $package_name, $provider, $configuration) {
        
        try {

            if ( !self::validateProvider($configuration) ) throw new InstallerException('Skipping invalid authentication provider '.$provider.' in '.$package_name);

            $class = $configuration['class'];
            
            $description = empty($configuration['description']) ? null : $configuration['description'];

            $id = $this->getPackageInstaller()->authentication()->getByName($provider)->getId();
            
            $this->getPackageInstaller()->authentication()->update($id, $package_name, $provider, $class, $description);

            $io->write(" <comment>~</comment> updated authentication provider ".$provider);

        } catch (Exception $e) {

            $io->write('<error>Error processing authentication provider: '.$e->getMessage().'</error>');

        }
        
    }
    
    private static function validateProvider($auth) {

        return !( empty($auth["class"]) );

    }

}
