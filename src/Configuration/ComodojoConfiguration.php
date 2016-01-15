<?php namespace Comodojo\Installer\Configuration;

use \Comodojo\Exception\InstallerException;
use \Comodojo\Configuration\Settings;
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

    public static function addSetting($setting, $value) {

        try {

            Settings::addSetting($setting, $value);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

    public static function removeSetting($setting, $value) {

        try {

            Settings::removeSetting($setting, $value);

        } catch (ConfigurationException $ce) {

            throw $ce;

        }

    }

}
