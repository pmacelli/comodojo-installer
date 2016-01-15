<?php namespace Comodojo\Installer\Configuration;

use \Comodojo\Exception\InstallerException;
use \Comodojo\Configuration\Settings;
use \Comodojo\Configuration\Themes;
use \Comodojo\Configuration\Rpc;
use \Comodojo\Configuration\Apps;
use \Comodojo\Configuration\Authentication;
use \Comodojo\Installer\Configuration\Filesystem;
use \Comodojo\Exception\ConfigurationException;
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

class ComodojoConfiguration {

    public static function addSetting($package_name, $setting, $value) {

        try {

            Settings::addSetting($setting, $value, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function removeSetting($package_name, $setting, $value) {

        try {

            Settings::removeSetting($setting, $value, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }
    
    public static function addTheme($package_name, $theme, $path) {

        $description = empty($theme['description']) ? null : $theme['description'];
        
        $fs = new Filesystem();
        
        $assets = $theme['assets'];

        try {

            Themes::addTheme($theme['name'], $description, $package_name);
            
            $fs->rcopy($path.'/'.$assets, COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_THEME_ASSETS.'/'.$name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function removeTheme($package_name, $theme) {

        $description = empty($theme['description']) ? null : $theme['description'];

        try {

            $fs->rmdir(COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_THEME_ASSETS.'/'.$name);

            Themes::removeTheme($theme['name'], $description, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function addRpc($package_name, $rpc) {
        
        $name = $rpc['name'];
        
        $callback = $rpc['callback'];
        
        $method = empty($rpc['method']) ? null : $rpc['method'];
        
        $description = empty($rpc['description']) ? null : $rpc['description'];
        
        $signatures = empty($rpc['signatures']) ? array() : $rpc['signatures'];
        
        try {

            Rpc::addRpc($name, $callback, $method, $description, $signatures, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }
        
    }

    public static function removeRpc($package_name, $rpc) {
        
        $name = $rpc['name'];
        
        try {

            Rpc::removeRpc($name, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }
        
    }
    
    public static function addApp($package_name, $app, $path) {
        
        $name = $app['name'];
        
        $description = empty($app['description']) ? null : $app['description'];
        
        $assets = $app['assets'];
        
        $fs = new Filesystem();
        
        try {
            
            Apps::addApp($name, $description, $package_name);
            
            $fs->rcopy($path.'/'.$assets, COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_APP_ASSETS.'/'.$name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        } catch (Exception $e) {

            throw $e;

        }
        
    }
    
    public static function removeApp($package_name, $app, $path) {
        
        $name = $app['name'];
        
        $fs = new Filesystem();
        
        try {
            
            $fs->rmdir(COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_APP_ASSETS.'/'.$name);
            
            Apps::removeApp($name, $package_name);
            
        } catch (ConfigurationException $ce) {

            throw $ce;

        } catch (Exception $e) {

            throw $e;

        }
        
    }
    
    public static function updateApp($package_name, $app, $path) {
        
        $name = $app['name'];
        
        $description = empty($app['description']) ? null : $app['description'];

        $fs = new Filesystem();
        
        try {
            
            $fs->rmdir(COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_APP_ASSETS.'/'.$name);
            
            Apps::updateApp($name, $description, $package_name);
            
            $fs->rcopy($path.'/'.$assets, COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_APP_ASSETS.'/'.$name);
            
        } catch (ConfigurationException $ce) {

            throw $ce;

        } catch (Exception $e) {

            throw $e;

        }
        
    }

    public static function addAuthentication($package_name, $auth) {
        
        $name = $auth['name'];
        
        $description = empty($auth['description']) ? null : $auth['description'];
        
        $class = $auth['class'];
        
        try {
            
            Authentication::addAuthProvider($name, $description, $class, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }
        
    }
    
    public static function removeAuthentication($package_name, $auth) {
        
        $name = $auth['name'];
        
        try {
            
            Authentication::removeAuthProvider($name, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }
        
    }
    
    public static function updateAuthentication($package_name, $auth) {
        
        $name = $auth['name'];
        
        $description = empty($auth['description']) ? null : $auth['description'];
        
        $class = $auth['class'];
        
        try {
            
            Authentication::updateAuthProvider($name, $description, $class, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }
        
    }

}
