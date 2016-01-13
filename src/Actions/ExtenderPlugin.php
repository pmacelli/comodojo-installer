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

class ExtenderPlugin extends AbstractAction {

    public function install($package_name, $package_extra) {
        
        $io = $this->getIO();

        $io->write(">>> Enabling plugins of package ".$package_name);

        foreach ($package_extra as $plugin) {
            
            try {
                
                if ( !self::validatePlugin($plugin) ) throw new InstallerException('Skipping invalid plugin in '.$package_name);
            
                ExtenderConfiguration::addPlugin($plugin);
                
                $io->write("+ Enabled plugin ".$plugin["class"]."::".$plugin["method"]." on event ".$plugin["event"]);
               
            } catch (Exception $e) {
                
                $this->getIO()->write('<error>'.$e->getMessage().'</error>', false);
                
            }
            
        }

    }

    public function update($package_name, $initial_extra, $target_extra) {
        
        $io = $this->getIO();

        $io->write(">>> Updating plugins of package ".$package_name);

        $this->uninstall($package_name, $initial_extra);
        
        $this->install($package_name, $target_extra);

    }

    public function uninstall($package_name, $package_extra) {

        foreach ($package_extra as $plugin) {
            
            try {
                
                if ( !self::validatePlugin($plugin) ) throw new InstallerException('Skipping invalid plugin in '.$package_name);
            
                ExtenderConfiguration::removePlugin($plugin);
               
            } catch (Exception $e) {
                
                $this->getIO()->write('<error>'.$e->getMessage().'</error>');
                
            }
            
        }

    }
    
    private static function validatePlugin($plugin) {
        
        return !( empty($plugin["class"]) || empty($plugin["event"]) );
        
    }

}
