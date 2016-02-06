<?php namespace Comodojo\Installer\Scripts;

use \Comodojo\Dispatcher\Components\Configuration;
use \Symfony\Component\Yaml\Yaml;
use \Comodojo\Exception\InstallerException;

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

class StaticConfigurationDumper {

    public static function dump(Configuration $configuration) {

        $installer_wd = $configuration->get('installer-working-directory');

        $static_folder = $configuration->get('static-config');

        $config_file = $installer_wd.'/'.$static_folder.'/comodojo-config.yml';

        $configuration->set("authentication-key", self::generateKey());

        $configuration->set("private-key", self::generateKey());

        $configuration_array = $configuration->get();

        $yaml = Yaml::dump($configuration_array, 2);

        $action = file_put_contents($config_file, $yaml, LOCK_EX);

        if ( $action === false ) throw new InstallerException("Cannot write comodojo-config file!");

        return true;

    }

    private static function generateKey() {

        return md5(uniqid(rand(), true), 0);

    }

}
