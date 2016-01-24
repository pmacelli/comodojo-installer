<?php namespace Comodojo\Installer\Parser;

use Composer\Package\PackageInterface;
use Comodojo\Installer\Registry\SupportedTypes;

/**
 *
 *
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

class Parser {

    public static function parse(PackageInterface $package) {

        $type = $package->getType();

        $extra = $package->getExtra();

        $map = array();

        foreach (SupportedTypes::getActions($type) as $field => $action) {

            if ( isset($extra[$field]) ) $map[$action] = $extra[$field];

        }

        return $map;

    }

}
