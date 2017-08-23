<?php namespace Comodojo\Installer\Drivers;

use \Comodojo\Foundation\Utils\ArrayOps;

/**
 * @package     Comodojo Framework
 * @author      Marco Giovinazzi <marco.giovinazzi@comodojo.org>
 * @license     MIT
 *
 * LICENSE:
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class TaskDriver extends AbstractDriver {

    // This is an example of how the plugin definition inside the composer.json should be implemented
    //
    //  "extra": {
    //      "tasks": [
    //         "mytask": {
    //              "description": "My first task!",
    //              "class": "\\My\\Namespace\\MyTask"
    //          }
    //      ]
    //  }

    protected $base_task = [
        "class" => null,
        "description" => null
    ];

    public function install($package_name, $package_path, array $package_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Installing tasks from package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->installTasks($config, $package_name, $package_extra);

        $io->write("<info>>>> Saving tasks configuration</info>");
        $persistence->dump($config);

    }

    public function update($package_name, $package_path, array $initial_extra, array $target_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Updating tasks from package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->uninstallTasks($config, $package_name, $initial_extra);
        $config = $this->installTasks($config, $package_name, $target_extra);

        $io->write("<info>>>> Saving tasks configuration</info>");
        $persistence->dump($config);

    }

    public function uninstall($package_name, $package_path, array $package_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Removing tasks from package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->uninstallTasks($config, $package_name, $package_extra);

        $io->write("<info>>>> Saving tasks configuration</info>");
        $persistence->dump($config);

    }

    protected function installTasks($config, $package_name, array $package_extra) {

        $io = $this->getIO();

        foreach ($package_extra as $name => $specs) {

            if ( array_key_exists($name, $config) ) {
                $io->write("<error>Duplicate task found!</error>");
                $io->write("<error>----------------------------</error>");
                $io->write("<error>Task $name in $package_name is already provided by ".$config[$name]["package_name"]."</error>");
                $io->write("<error>----------------------------</error>");
                continue;
            }

            if ( !self::validate($specs) ) {
                $io->write("<error>Invalid task definition in $package_name</error>");
                $io->write("<error>----------------------------</error>");
                $io->write("<error>$name => ".var_export($specs, true)."</error>");
                $io->write("<error>----------------------------</error>");
                continue;
            }

            $raw_task = ArrayOps::replaceStrict($this->base_task, $specs);
            $config[$name] = self::buildTask($name, $raw_task, $package_name);
            $io->write(" <info>+</info> enabled task [$name]");

        }

        return $config;

    }

    protected function uninstallTasks($config, $package_name, array $package_extra) {

        $io = $this->getIO();

        foreach ($package_extra as $name => $specs) {

            $exists = array_key_exists($name, $config);

            if ( $exists && $config[$name]["package_name"] == $package_name) {
                unset($config[$name]);
                $io->write(" <info>-</info> Task [$name] removed");
            } else if ( $exists ) {
                $io->write("<error>Task [$name] does not belong to $package_name and cannot be removed</error>");
                $io->write("<error>----------------------------</error>");
                $io->write("<error>Found existing task $name that belongs to ".$config[$name]["package_name"]);
                $io->write("<error>----------------------------</error>");
            } else {
                $io->write("<warning>!</warning> Missing task [$name]");
            }

        }

        return $config;

    }

    protected static function buildTask($name, $raw_task, $package_name) {

        $raw_task["package_name"] = $package_name;

        return $raw_task;

    }


    protected static function validate(array $extra) {
        return !empty($extra["class"]);
    }

}
