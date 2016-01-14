<?php namespace Comodojo\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

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

class Plugin implements PluginInterface {

    public function activate(Composer $composer, IOInterface $io) {

        $this->loadInstallerConfig($composer);

        if ( !$this->loadStaticConfiguration($composer) ) {

            $this->getIO()->write('<comment>Comodojo configuration not (yet) available.</comment>');

        }

        $installer = new Installer($io, $composer);

        $composer->getInstallationManager()->addInstaller($installer);

    }

    private function loadInstallerConfig(Composer $composer) {

        $extra = $composer->getPackage()->getExtra();

        $installer_default_config = array(
            'app-assets' => 'public/apps',
            'framework-js' => 'public/js',
            'framework-templates' => 'public/templates',
            'local-cache' => 'cache',
            'static-config' => 'config',
            'local-logs' => 'logs',
            'local-database': 'database'
        );

        if ( isset($extra['comodojo-installer-paths']) && is_array($extra['comodojo-installer-paths']) ) {

            $installer_config = array_replace($installer_default_config, $extra['comodojo-installer-paths']);

        } else {

            $installer_config = $installer_default_config;

        }

        define('COMODOJO_INSTALLER_APP_ASSETS', $installer_config['app-assets']);

        define('COMODOJO_INSTALLER_FRAMEWORK_JS', $installer_config['framework-js']);

        define('COMODOJO_INSTALLER_FRAMEWORK_TEMPLATES', $installer_config['framework-templates']);

        define('COMODOJO_INSTALLER_LOCAL_CACHE', $installer_config['local-cache']);

        define('COMODOJO_INSTALLER_STATIC_CONFIG', $installer_config['static-config']);

        define('COMODOJO_INSTALLER_LOCAL_LOGS', $installer_config['local-logs']);

        define('COMODOJO_INSTALLER_LOCAL_DATABASE', $installer_config['local-database']);

        define('COMODOJO_INSTALLER_WORKING_DIRECTORY', getcwd());

    }

    private function loadStaticConfiguration() {

        $config_file = COMODOJO_INSTALLER_WORKING_DIRECTORY.COMODOJO_INSTALLER_STATIC_CONFIG.'/config.php';

        if ( is_file($config_file) && is_readable($config_file) ) {

            include_once($config_file);

            return true;

        }

        return false;

    }






}
