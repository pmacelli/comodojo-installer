<?php namespace Comodojo\Installer\Scripts;

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

    private $settings = array(
        'COMODOJO_REAL_PATH' => COMODOJO_INSTALLER_WORKING_DIRECTORY,
        'COMODOJO_STATIC_CONFIG' => COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_STATIC_CONFIG,
        'COMODOJO_LOCAL_CACHE' => COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_LOCAL_CACHE,
        'COMODOJO_LOCAL_LOGS' => COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_LOCAL_LOGS,
        'COMODOJO_LOCAL_DATABASE' => COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_LOCAL_DATABASE,

        'COMODOJO_DATABASE_MODEL' => 'MYSQL',
        'COMODOJO_DATABASE_HOST' => 'localhost',
        'COMODOJO_DATABASE_PORT' => 3306,
        'COMODOJO_DATABASE_NAME' => 'comodojo',
        'COMODOJO_DATABASE_USER' => 'comodojo',
        'COMODOJO_DATABASE_PASS' => 'comodojo',
        'COMODOJO_DATABASE_PREFIX' => "cmdj_",

        'COMODOJO_APP_ASSETS' => COMODOJO_INSTALLER_APP_ASSETS,
        'COMODOJO_THEME_ASSETS' => COMODOJO_INSTALLER_THEME_ASSETS
    );

    public function __set($setting, $value) {

        $this->$settings[$setting] = $value;

        return $this;

    }

    public function __get($setting) {

        if (array_key_exists($setting, $this->$settings)) {

            return $this->$settings[$setting];

        }

        return null;

    }

    public function __isset($setting) {

        return isset($this->$settings[$setting]);

    }

    public function persist() {

        $config_file = COMODOJO_INSTALLER_WORKING_DIRECTORY.'/'.COMODOJO_INSTALLER_STATIC_CONFIG.'/'.comodojo-config.php;

        $template = $this->loadConfigurationTemplate();

        foreach ($this->settings as $setting => $value) {

            $template = str_replace('_'.$setting.'_', $value, $template);

        }

        $action = file_put_contents($config_file, $template, LOCK_EX);

        if ( $action === false ) throw new InstallerException("Cannot write comodojo-config file!");

        return true;

    }

    private function loadConfigurationTemplate() {

        $template_file = realpath(dirname(__FILE__)."/../../")."/comodojo-config.template";

        $template = file_get_contents($template_file);

        if ( $template === false ) throw new InstallerException("Cannot read comodojo-config template!");

        return $template;

    }

}
