<?php namespace Comodojo\Installer\Scripts;

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

class InteractiveConfiguration {

    public static function postCreateProjectCmd(PackageEvent $event) {

        $io = $event->getIO();
        
        $params = array();
        
        $io->write("<info>Starting comodojo base configuration.
            Please answer the following questions as accurately and honestly as possible...</info>");
        
        $params['COMODOJO_DATABASE_HOST'] = $io->ask("Database host?", "localhost");
        
        $params['COMODOJO_DATABASE_PORT'] = $io->askAndValidate("Database port?", function($value) {
            return is_int($value);
        }, 3, 3306);
        
        $params['COMODOJO_DATABASE_NAME'] = $io->ask("Database name?", "comodojo");
        
        $params['COMODOJO_DATABASE_USER'] = $io->ask("Database user?", "comodojo");
        
        $params['COMODOJO_DATABASE_PASS'] = $io->ask("Database password?", "");
        
        $params['COMODOJO_DATABASE_PREFIX'] = $io->ask("Common prefix for database tables?", "cmdj_");
        
        $dumper = new StaticConfiguartionDumper($params);
        
        $dumper->dump();
        
        $io->write("<info>Configuartion completed. Remember to exec 'php comodojo.php install' to install framework. Have fun.</info>");

    }

}
