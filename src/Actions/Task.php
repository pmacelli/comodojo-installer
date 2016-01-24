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

class Task extends AbstractAction {

    public function install($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Installing (extender) tasks from package ".$package_name."</info>");

        $this->processTask($io, 'install', $package_name, $package_extra);

    }

    public function update($package_name, $initial_extra, $target_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Updating (extender) tasks from package ".$package_name."</info>");

        $this->processTask($io, 'uninstall', $package_name, $initial_extra);
        
        $this->processTask($io, 'install', $package_name, $target_extra);

    }

    public function uninstall($package_name, $package_extra) {

        $io = $this->getIO();

        $io->write("<info>>>> Removing (extender) tasks from package ".$package_name."</info>");

        $this->processTask($io, 'uninstall', $package_name, $package_extra);

    }

    private function processTask($io, $action, $package_name, $package_extra) {

        foreach ($package_extra as $name => $task) {

            try {

                if ( !self::validateTask($task) ) throw new InstallerException('Skipping invalid task in '.$package_name);
                
                $class = $task['class'];
                
                $description = empty($task['description']) ? null : $task['description'];

                switch ($action) {

                    case 'install':
                        
                        $this->getPackageInstaller()->tasks()->add($package_name, $name, $class, $description);

                        $io->write(" <info>+</info> enabled task ".$name);

                        break;

                    case 'uninstall':
                        
                        $id = $this->getPackageInstaller()->tasks()->getByName($name)->getId();

                        $this->getPackageInstaller()->tasks()->delete($id);

                        $io->write(" <comment>-</comment> disabled task ".$name." (id ".$id.")");

                        break;

                }

            } catch (Exception $e) {

                $io->write('<error>Error processing task: '.$e->getMessage().'</error>');

            }

        }

    }

    private static function validateTask($task) {

        return !( empty($task["class"]) );

    }

}
