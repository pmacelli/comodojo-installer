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

class ComodojoApp extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing authentication providers from ".$package_name."</info>");

        self::processAuthentication($io, 'install', $package_name, $package_extra);

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating authentication providers from ".$package_name."</info>");

        self::processAuthenticationUpdate($io, $package_name, $initial_extra, $target_extra);

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing authentication providers from ".$package_name."</info>");

        self::processAuthentication($io, 'uninstall', $package_name, $package_extra);

    }

    private static function processApp($io, $action, $package_name, $package_extra) {

        foreach ($package_extra as $name => $auth) {
            
            switch ($action) {

                case 'install':
                    
                    self::addAuthProvider($io, $package_name, $auth);

                    break;
                    
                case 'uninstall':

                    self::removeAuthProvider($io, $package_name, $auth);

                    break;

            }

        }

    }
    
    private static function processAuthenticationUpdate($io, $package_name, $initial_extra, $target_extra) {
        
        $old_auth = array_keys($initial_extra);
        
        $new_auth = array_keys($target_extra);
        
        $uninstall = array_diff($old_auth, $new_auth);

        $install = array_diff($new_auth, $old_auth);

        $update = array_intersect($old_auth, $new_auth);
        
        foreach ( $uninstall as $auth ) {
            
            self::removeAuthProvider($io, $package_name, array($auth, $initial_extra[$auth]));
            
        }
        
        foreach ( $install as $auth ) {
            
            self::addAuthProvider($io, $package_name, array($auth, $target_extra[$auth]));
            
        }
        
        foreach ( $update as $auth ) {
            
            self::updateAuthProvider($package_name, $target_extra[$auth]);
            
        }
        
    }
    
    private static function addAuthProvider($io, $package_name, $auth) {
        
        try {

            if ( !self::validateProvider($auth) ) throw new InstallerException('Skipping invalid authentication provider in '.$package_name);

            ComodojoConfiguration::addAuthentication($package_name, $auth);

            $io->write(" <info>+</info> added authentication provider ".$auth['name']);

        } catch (Exception $e) {

            $io->write('<error>Error processing authentication provider: '.$e->getMessage().'</error>');

        }
        
    }
    
    private static function removeAuthProvider($io, $package_name, $auth) {
        
        try {

            if ( !self::validateProvider($auth) ) throw new InstallerException('Skipping invalid authentication provider in '.$package_name);

            ComodojoConfiguration::removeAuthentication($package_name, $auth);

            $io->write(" <comment>-</comment> removed authentication provider ".$auth['name']);

        } catch (Exception $e) {

            $io->write('<error>Error processing authentication provider: '.$e->getMessage().'</error>');

        }
        
    }
    
    private static function updateAuthProvider($io, $package_name, $auth) {
        
        try {

            if ( !self::validateProvider($auth) ) throw new InstallerException('Skipping invalid authentication provider in '.$package_name);

            ComodojoConfiguration::updateAuthentication($package_name, $auth);

            $io->write(" <comment>~</comment> updated authentication provider ".$auth['name']);

        } catch (Exception $e) {

            $io->write('<error>Error processing authentication provider: '.$e->getMessage().'</error>');

        }
        
    }
    
    private static function validateProvider($auth) {

        return !( empty($auth["name"]) || empty($auth["class"]) );

    }

}
