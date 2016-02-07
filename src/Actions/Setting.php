<?php namespace Comodojo\Installer\Actions;

use \Comodojo\Exception\InstallerException;
use \Exception;

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

class Setting extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing setting from package ".$package_name."</info>");

        foreach ($package_extra as $setting => $values) {

            $this->addSetting($io, $package_name, $setting, $values);

        }

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating setting from package ".$package_name."</info>");

        $old_settings = array_keys($initial_extra);

        $new_settings = array_keys($target_extra);

        $uninstall = array_diff($old_settings, $new_settings);

        $install = array_diff($new_settings, $old_settings);

        $update = array_intersect($old_settings, $new_settings);

        foreach ( $uninstall as $setting ) {

            $this->removeSetting($io, $package_name, $setting, $initial_extra[$setting]);

        }

        foreach ( $install as $setting ) {

            $this->addSetting($io, $package_name, $setting, $target_extra[$setting]);

        }

        foreach ( $update as $setting ) {

            $this->updateSetting($io, $package_name, $setting, $initial_extra[$setting], $target_extra[$setting]);

        }

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing setting from package ".$package_name."</info>");

        foreach ($package_extra as $setting => $values) {

            $this->addSetting($io, $package_name, $setting, $value);

        }

    }

    private function addSetting($io, $package_name, $setting, $values) {

        try {

            if ( !self::validateSetting($values) ) throw new InstallerException('Skipping invalid setting '.$setting.' in '.$package_name);

            $value = $values["value"];
            $constant = isset($values["constant"]) ? filter_var($values["constant"], FILTER_VALIDATE_BOOLEAN) : false;
            $type = $values["type"];
            $validate = isset($values["validate"]) ? $values["validate"] : null;

            $this->getPackageInstaller()->settings()->add($package_name, $setting, $value, $constant, $type, $validate);

            $io->write(" <info>+</info> added setting ".$setting);

        } catch (Exception $e) {

            $io->write('<error>Error processing setting: '.$e->getMessage().'</error>');

        }

    }

    private function removeSetting($io, $package_name, $setting) {

        try {

            if ( !self::validateSetting($value) ) throw new InstallerException('Skipping invalid setting '.$setting.' in '.$package_name);

            $id = $this->getPackageInstaller()->settings()->getByName($setting)->getId();

            $this->getPackageInstaller()->settings()->delete($id);

            $io->write(" <comment>-</comment> removed setting ".$setting);

        } catch (Exception $e) {

            $io->write('<error>Error processing setting: '.$e->getMessage().'</error>');

        }

    }

    private function updateSetting($io, $package_name, $setting, $old_values, $new_values) {

        try {

            if ( !self::validateSetting($new_values) ) throw new InstallerException('Skipping invalid setting '.$setting.' in '.$package_name);

            $value = $new_values["value"];
            $constant = isset($new_values["constant"]) ? filter_var($new_values["constant"], FILTER_VALIDATE_BOOLEAN) : false;
            $type = $new_values["type"];
            $validate = isset($new_values["validate"]) ? $new_values["validate"] : null;

            $old = $this->getPackageInstaller()->settings()->getByName($setting);

            if ( $old->getValue() == $old_values['value'] ) {

                $this->getPackageInstaller()->settings()->update($old->getId(), $package_name, $setting, $value, $constant, $type, $validate);

                $io->write(" <comment>~</comment> updated setting ".$setting);

            } else if ( $io->askConfirmation("Replace modified setting ".$setting."?", false) === true ) {

                $this->getPackageInstaller()->settings()->update($old->getId(), $package_name, $setting, $value, $constant, $type, $validate);

                $io->write(" <comment>~</comment> updated setting ".$setting);

            } else {

                $io->write(" <comment>x</comment> modified setting ".$setting." not replaced");

            }

        } catch (Exception $e) {

            $io->write('<error>Error processing setting: '.$e->getMessage().'</error>');

        }

    }

    private static function validateSetting($value) {

        return !( empty($value["value"]) || empty($value["type"]) );

    }

}
