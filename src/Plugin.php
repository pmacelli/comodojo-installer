<?php namespace Comodojo\Installer;

use \Composer\Composer;
use \Composer\IO\IOInterface;
use \Composer\Plugin\PluginInterface;
use \Composer\Plugin\PluginEvents;
use \Composer\EventDispatcher\EventSubscriberInterface;
use \Composer\EventDispatcher\Event;
use \Comodojo\Configuration\Installer as PackageInstaller;
use \Comodojo\Dispatcher\Components\Configuration;
use \Comodojo\Installer\Scripts\InteractiveConfiguration;
use \Comodojo\Installer\Scripts\StaticConfigurationDumper;
use \Symfony\Component\Yaml\Yaml;

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

class Plugin implements PluginInterface, EventSubscriberInterface {

    public function activate(Composer $composer, IOInterface $io) {

        $this->configuration = $this->loadInstallerConfig($composer);

        if ( !$this->loadStaticConfiguration($this->configuration) ) {

            $io->write('<comment>Comodojo configuration not (yet) available.</comment>');

            $installer = new Installer($composer, $io);

        } else {

            $package_installer = new PackageInstaller($this->configuration);

            $installer = new Installer($composer, $io, $package_installer);

            $io->write('<comment>Comodojo configuration loaded, installer ready.</comment>');

        }

        $composer->getInstallationManager()->addInstaller($installer);

    }

    public static function getSubscribedEvents() {

        return array(
            'post-create-project-cmd' => array(
                array('startInteractiveCommands', 0)
            )
        );

    }

    public function startInteractiveCommands(Event $event) {

        $io = $event->getIO();

        InteractiveConfiguration::start($this->configuration, $io);

        StaticConfiguartionDumper::dump($this->configuration);

        $io->write("<info>Static configuration dumped.");
        $io->write("Remember to exec 'php comodojo.php install' to complete installation of framework.");
        $io->write("Have fun!</info>");

    }

    private function loadInstallerConfig(Composer $composer) {

        $extra = $composer->getPackage()->getExtra();

        $installer_default_config = array(
            'app-assets' => 'public/apps',
            'theme-assets' => 'public/themes',
            'local-cache' => 'cache',
            'static-config' => 'config',
            'local-logs' => 'logs',
            'local-database' => 'database'
        );

        if ( isset($extra['comodojo-installer-paths']) && is_array($extra['comodojo-installer-paths']) ) {

            $installer_config = array_replace($installer_default_config, $extra['comodojo-installer-paths']);

        } else {

            $installer_config = $installer_default_config;

        }

        $configuration = new Configuration();

        foreach ( $installer_config as $setting => $value ) {

            $configuration->set($setting, $value);

        }

        $configuration->set('installer-working-directory', getcwd());

        return $configuration;

    }

    private function loadStaticConfiguration(Configuration $configuration) {

        $installer_wd = $configuration->get('installer-working-directory');

        $static_folder = $configuration->get('static-config');

        $config_file = $installer_wd.'/'.$static_folder.'/comodojo-config.yml';

        if ( is_file($config_file) && is_readable($config_file) && $yaml = file_get_contents($config_file) ) {

            $data = Yaml::parse($yaml);

            foreach( $data as $parameter => $value ) {

                $this->configuration->set($parameter, $value);

            }

            return true;

        }

        return false;

    }

}
