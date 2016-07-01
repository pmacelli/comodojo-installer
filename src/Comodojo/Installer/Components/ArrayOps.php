<?php namespace Comodojo\Installer\Components;

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

class ArrayOps {

    public static function arrayCircularDiffKey($left, $right) {

        return array(
            // only in left
            array_diff_key($left, $right),
            // common keys
            array_intersect_key($left, $right),
            // only in right
            array_diff_key($right, $left)
        );

    }

    public static function arrayFilterByKey($array_of_keys, $array_to_filter) {

        return array_intersect_key($array_to_filter, array_flip($array_of_keys));

    }
}
