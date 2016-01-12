<?php namespace Comodojo\Installer\Parser;

use Composer\Package\PackageInterface;

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

class Parser {

    private static $supported_actions_by_type = array(
        'extender-plugins-bundle' = array(
            "comodojo-plugins-load" => "ExtenderPlugin",
            "extender-plugin-load" => "ExtenderPlugin"
        ),
	    'extender-tasks-bundle' = array(
            "comodojo-tasks-register" => "ExtenderTask",
            "extender-task-register" => "ExtenderTask"
        ),
	    'extender-commands-bundle' = array(
            "comodojo-commands-register" => "ExtenderCommand",
            "extender-command-register" => "ExtenderCommand"
        ),
	    'dispatcher-plugin' = array(
            "comodojo-plugin-load" => "DispatcherPlugin",
            "dispatcher-plugin-load" => "DispatcherPlugin"
        ),
	    'dispatcher-service-bundle' = array(
            "comodojo-service-route" => "DispatcherService",
            "dispatcher-service-route" => "DispatcherService"
        ),
        'comodojo-app' = array(
            "comodojo-app-register" => "ComodojoApp",
            "comodojo-configuration-register" => "ComodojoConfiguration"
        ),
	    'comodojo-bundle' = array(
            "dispatcher-plugin-load" => "DispatcherPlugin",
            "dispatcher-service-route" => "DispatcherService",
            "extender-plugin-load" => "ExtenderPlugin",
            "extender-command-register" => "ExtenderCommand",
            "extender-task-register" => "ExtenderTask",
            "comodojo-app-register" => "ComodojoApp",
            "comodojo-configuration-register" => "ComodojoConfiguration"
        )
    );

    public static function parse(PackageInterface $package) {

        $type = $package->getType();

        $extra = $package->getExtra();

        $map = array();

        foreach (self::supported_actions_by_type[$type] as $field => $action) {

            if ( isset($extra[$field]) ) $map[$action] = $extra[$field];

        }

        return $map;

    }

}
