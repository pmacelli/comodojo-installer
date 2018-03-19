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

class CommandDriver extends AbstractDriver {

    // This is an example of how the command definition inside the composer.json should be implemented
    //
    //  "extra": {
    //      "commands": [
    //          {
    //              "class": "\\My\\Command",
    //              "scope": "dispatcher" // <= this command will be loaded by dispatcher exec only
    //          },
    //          {
    //              "class": "\\My\\Command",
    //              "scope": "extender" // <= this command will be loaded by extender exec only
    //          },
    //          {
    //              "class": "\\My\\Command" // <= this command will be loaded by every exec
    //          },
    //          {
    //              "class": "\\My\\Command",
    //              "scope": "any" // <= this command will be loaded by every exec
    //          }
    //      ]
    //  }

    protected $base_cmd = [
        "class" => null,
        "scope" => 'any'
    ];

    public function install($package_name, $package_path, array $package_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Installing commands from package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->manageCommands('install', $config, $package_name, $package_extra);

        $io->write("<info>>>> Saving commands configuration</info>");
        $persistence->dump($config);

    }

    public function update($package_name, $package_path, array $initial_extra, array $target_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Updating commands from package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->manageCommands('uninstall', $config, $package_name, $initial_extra);
        $config = $this->manageCommands('install', $config, $package_name, $target_extra);

        $io->write("<info>>>> Saving commands configuration</info>");
        $persistence->dump($config);

    }

    public function uninstall($package_name, $package_path, array $package_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Removing commands from package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->manageCommands('uninstall', $config, $package_name, $package_extra);

        $io->write("<info>>>> Saving commands configuration</info>");
        $persistence->dump($config);

    }

    protected function manageCommands($action, $config, $package_name, array $package_extra) {

        $io = $this->getIO();

        switch ($action) {

            case 'install':

                $config[$package_name] = [];

                foreach ($package_extra as $command) {

                    if ( !self::validate($command) ) {
                        $io->write("<error>Invalid command definition in $package_name</error>");
                        $io->write("<error>----------------------------</error>");
                        $io->write("<error>".var_export($command, true)."</error>");
                        $io->write("<error>----------------------------</error>");
                        continue;
                    }

                    $config[$package_name][] = ArrayOps::replaceStrict($this->base_cmd, $command);
                    $io->write(" <info>+</info> enabled command [".$command["class"]."] for exec ".$command["scope"]);

                }

                break;

            case 'uninstall':

                unset($config[$package_name]);

                break;

        }

        return $config;

    }

    protected static function validate(array $extra) {
        return !empty($extra["class"]);
    }

}
