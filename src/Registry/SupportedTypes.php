<?php namespace Comodojo\Installer\Registry;

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

class SupportedTypes {

    private static $supported_actions_by_type = array(

        'extender-plugins-bundle' => array(
            "comodojo-plugins-load" => "ExtenderPlugin",
            "extender-plugin-load" => "ExtenderPlugin"
        ),

	    'extender-tasks-bundle' => array(
            "comodojo-tasks-register" => "ExtenderTask",
            "extender-task-register" => "ExtenderTask"
        ),

	    'extender-commands-bundle' => array(
            "comodojo-commands-register" => "ExtenderCommand",
            "extender-command-register" => "ExtenderCommand"
        ),

	    'dispatcher-plugin' => array(
            "comodojo-plugin-load" => "DispatcherPlugin",
            "dispatcher-plugin-load" => "DispatcherPlugin"
        ),

	    'dispatcher-service-bundle' => array(
            "comodojo-service-route" => "DispatcherService",
            "dispatcher-service-route" => "DispatcherService"
        ),

        'comodojo-app' => array(
            "comodojo-app-register" => "ComodojoApp",
            "comodojo-configuration-register" => "ComodojoSetting",
            "comodojo-rpc-register" => "ComodojoRpc",
            "comodojo-service-route" => "DispatcherService",
            "comodojo-task-register" => "ExtenderTask",
            "comodojo-command-register" => "ExtenderCommand"
        ),

        'comodojo-component' => array(
            "comodojo-theme-register" => "ComodojoTheme",
            "comodojo-authentication-register" => "ComodojoAuthentication"
        ),

	    'comodojo-bundle' => array(
            "dispatcher-plugin-load" => "DispatcherPlugin",
            "dispatcher-service-route" => "DispatcherService",
            "extender-plugin-load" => "ExtenderPlugin",
            "extender-command-register" => "ExtenderCommand",
            "extender-task-register" => "ExtenderTask",
            "comodojo-app-register" => "ComodojoApp",
            "comodojo-configuration-register" => "ComodojoSetting",
            "comodojo-rpc-register" => "ComodojoRpc",
            "comodojo-service-route" => "DispatcherService",
            "comodojo-task-register" => "ExtenderTask",
            "comodojo-command-register" => "ExtenderCommand",
            "comodojo-theme-register" => "ComodojoTheme",
            "comodojo-authentication-register" => "ComodojoAuthentication"
        )

    );

    public static function getTypes() {

        return array_keys(self::$supported_actions_by_type);

    }

    public static function getActions($type) {

        return isset(self::$supported_actions_by_type[$type]) ? self::$supported_actions_by_type[$type] : array();

    }

}
