<?php namespace Comodojo\Installer\Configuration;

use \Comodojo\Exception\InstallerException;
use \Comodojo\Configuration\Dispatcher;
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

class DispatcherConfiguration {

    public static function addRoute($package_name, $service) {

        $parameters = empty($service['parameters']) ? array() : $service['parameters'];

        try {

            Dispatcher::addRoute($service['name'], $service['path'], $service['type'], $service['target'], $parameters, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function removeRoute($package_name, $service) {

        try {

            Dispatcher::removeRoute($service['name'], $service['path'], $service['type'], $service['target'], $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function addPlugin($package_name, $plugin) {

        $method = empty($plugin['method']) ? null : $plugin['method'];
        
        try {
            
            Dispatcher::addPlugin($plugin['event'], $plugin['class'], $method, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function removePlugin($package_name, $plugin) {

        $method = empty($plugin['method']) ? null : $plugin['method'];

        try {

            Dispatcher::removePlugin($task['event'], $task['class'], $method, $package_name);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

}
