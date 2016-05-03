<?php namespace Comodojo\Installer\Registry;

/**
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

class SupportedActions {

    protected static $actions = array() {
        'comodojo-dispatcher-plugin-load' => 'Comodojo\\Installer\\Actions\\DispatcherPlugin',
        'comodojo-extender-plugin-load' => 'Comodojo\\Installer\\Actions\\ExtenderPlugin',
        'comodojo-application-register' => 'Comodojo\\Installer\\Actions\\App',
        'comodojo-configuration-register' => 'Comodojo\\Installer\\Actions\\Setting',
        'comodojo-rpc-register' => 'Comodojo\\Installer\\Actions\\Rpc',
        'comodojo-route-register' => 'Comodojo\\Installer\\Actions\\Route',
        'comodojo-task-register' => 'Comodojo\\Installer\\Actions\\Task',
        'comodojo-command-register' => 'Comodojo\\Installer\\Actions\\Command',
        'comodojo-theme-register' => 'Comodojo\\Installer\\Actions\\Theme',
        'comodojo-authentication-register' => 'Comodojo\\Installer\\Actions\\Authentication'
    }

}
