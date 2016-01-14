<?php namespace Comodojo\Installer\Configuration;

use \Comodojo\Exception\InstallerException;
use \Comodojo\Configuration\Extender;
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

class ExtenderConfiguration {

    public static function addTask($task) {

        try {

            Extender::addTask($task['name'], $task['class'], empty($task['description']) ? null, $task['description']);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function removeTask($task) {

        try {

            Extender::removeTask($task['name']);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function addPlugin($plugin) {

        try {

            Extender::addPlugin($plugin['event'], $plugin['class'], empty($plugin['method']) ? null : $plugin['method']);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function removePlugin($plugin) {

        try {

            Extender::removePlugin($task['event'], $task['class'], empty($task['method']) ? null : $task['method']);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function addCommand($command, $actions) {

        $class = $actions["class"];

        $description = empty($actions["description"]) ? null : $actions["description"];

        $aliases = array();

        if ( isset($actions["aliases"]) && @is_array($actions["aliases"]) ) {

            foreach ($actions["aliases"] as $alias) array_push($aliases, $alias);

        }

        $options = array();

        if ( isset($actions["options"]) && @is_array($actions["options"]) ) {

            foreach ($actions["options"] as $option => $oparameters) $options[$option] = $oparameters;

        }

        $arguments = array();

        if ( isset($actions["arguments"]) && @is_array($actions["arguments"]) ) {

            foreach ($actions["arguments"] as $argument => $aparameters) $arguments[$argument] = $aparameters;

        }

        try {

            Extender::addCommand($command, $class, $description, $aliases, $options, $arguments);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function removeCommand($command) {

        try {

            Extender::removeCommand($command);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

}
