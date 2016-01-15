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

        $io->write("<info>>>> Installing apps from ".$package_name."</info>");

        self::processApp($io, 'install', $package_name, $package_extra);

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating apps from ".$package_name."</info>");

        self::processAppUpdate($io, $package_name, $initial_extra, $target_extra);

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing apps from ".$package_name."</info>");

        self::processApp($io, 'uninstall', $package_name, $package_extra);

    }

    private static function processApp($io, $action, $package_name, $package_extra) {

        foreach ($package_extra as $name => $app) {
            
            switch ($action) {

                case 'install':
                    
                    self::addApp($io, $package_name, $app);

                    break;
                    
                case 'uninstall':

                    self::removeApp($io, $package_name, $app);

                    break;

            }

        }

    }
    
    private static function processAppUpdate($io, $package_name, $initial_extra, $target_extra) {
        
        $old_app = array_keys($initial_extra);
        
        $new_app = array_keys($target_extra);
        
        $uninstall = array_diff($old_app, $new_app);

        $install = array_diff($new_app, $old_app);

        $update = array_intersect($old_app, $new_app);
        
        foreach ( $uninstall as $app ) {
            
            self::removeApp($io, $package_name, array($app, $initial_extra[$app]));
            
        }
        
        foreach ( $install as $app ) {
            
            self::addApp($io, $package_name, array($app, $target_extra[$app]));
            
        }
        
        foreach ( $update as $app ) {
            
            self::updateApp($package_name, $target_extra[$app]);
            
        }
        
    }
    
    private static function addApp($io, $package_name, $app) {
        
        try {

            if ( !self::validateApp($app) ) throw new InstallerException('Skipping invalid app in '.$package_name);

            ComodojoConfiguration::addApp($package_name, $app, $this->getPath());

            $io->write(" <info>+</info> added app ".$app['name']);

        } catch (Exception $e) {

            $io->write('<error>Error processing app: '.$e->getMessage().'</error>');

        }
        
    }
    
    private static function removeApp($io, $package_name, $app) {
        
        try {

            if ( !self::validateApp($app) ) throw new InstallerException('Skipping invalid app in '.$package_name);

            ComodojoConfiguration::removeApp($package_name, $app, $this->getPath());

            $io->write(" <comment>-</comment> removed app ".$app['name']);

        } catch (Exception $e) {

            $io->write('<error>Error processing app: '.$e->getMessage().'</error>');

        }
        
    }
    
    private static function updateApp($io, $package_name, $app) {
        
        try {

            if ( !self::validateApp($app) ) throw new InstallerException('Skipping invalid app in '.$package_name);

            ComodojoConfiguration::updateApp($package_name, $app, $this->getPath());

            $io->write(" <comment>~</comment> updated app ".$app['name']);

        } catch (Exception $e) {

            $io->write('<error>Error processing app: '.$e->getMessage().'</error>');

        }
        
    }
    
    private static function validateApp($app) {

        return !( empty($app["name"]) || empty($app["assets"]) );

    }

}
