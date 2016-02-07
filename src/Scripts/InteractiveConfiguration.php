<?php namespace Comodojo\Installer\Scripts;

use \Comodojo\Dispatcher\Components\Configuration;
use \Composer\IO\IOInterface;

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

    public static function start(Configuration $configuration, IOInterface $io) {

        $params = array();

        $params['database-model'] = $io->ask("> Database model? (MYSQLI) ", "MYSQLI");

        $params['database-host'] = $io->ask("> Database host? (localhost) ", "localhost");

        $params['database-port'] = $io->askAndValidate("> Database port? (3306) ", function($value) {
            return is_int($value);
        }, 3, 3306);

        $params['database-name'] = $io->ask("> Database name? (comodojo) ", "comodojo");

        $params['database-user'] = $io->ask("> Database user? (comodojo) ", "comodojo");

        $params['database-password'] = $io->askAndHideAnswer("> Database password? ");

        $params['database-prefix'] = $io->ask("> Common prefix for database tables? (cmdj_) ", "cmdj_");

        foreach ($params as $param => $value) {

            $configuration->set($param, $value);

        }

    }

}
