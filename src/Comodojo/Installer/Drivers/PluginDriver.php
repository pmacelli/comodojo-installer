<?php namespace Comodojo\Installer\Drivers;

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

class PluginDriver extends AbstractDrivers {

    // This is an example of how the plugin definition inside the composer.json should be implemented
    //
    //  "extra": {
    //      "plugins": [
    //          {
    //              "class": "\\My\\Plugin",
    //              "event": "custom.event",
    //              "priority": 0,
    //              "onetime": false
    //          },
    //          {
    //              "class": "\\My\\OtherPlugin",
    //              "event": "custom.anotherevent"
    //          }
    //      ]
    //  }

    protected $base_plugin = [
        "class" => null,
        "event" => null,
        "priority" => 0,
        "onetime" => false
    ];

    public function install($package_name, $package_path, array $package_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Installing plugins from package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->managePlugins('install', $config, $package_name, $package_extra);

        $io->write("<info>>>> Saving plugins configuration</info>");
        $persistence->dump($config);

    }

    public function update($package_name, $package_path, array $initial_extra, array $target_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Updating plugins from package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->managePlugins('uninstall', $config, $package_name, $initial_extra);
        $config = $this->managePlugins('install', $config, $package_name, $target_extra);

        $io->write("<info>>>> Saving plugins configuration</info>");
        $persistence->dump($config);

    }

    public function uninstall($package_name, $package_path, array $package_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Removing plugins from package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->managePlugins('uninstall', $config, $package_name, $package_extra);

        $io->write("<info>>>> Saving plugins configuration</info>");
        $persistence->dump($config);

    }

    protected function managePlugins($action, $config, $package_name, array $package_extra) {

        $io = $this->getIO();

        switch ($action) {

            case 'install':

                foreach ($package_extra as $plugin) {

                    if ( !self::validate($plugin) ) {
                        $io->write("<error>Invalid plugin definition in $package_name</error>");
                        $io->write("<error>----------------------------</error>");
                        $io->write("<error>".var_export($plugin, true)."</error>");
                        $io->write("<error>----------------------------</error>");
                        continue;
                    }

                    $config[$package_name] = ArrayOps::replaceStrict($this->base_plugin, $plugin);
                    $io->write(" <info>+</info> enabled listener [".$plugin["class"]."] on event ".$plugin["event"]);

                }

                break;

            case 'uninstall':

                unset($config[$package_name]);

                break;

        }

        return $config;

    }

    protected static function validate(array $extra) {
        return !( empty($extra["class"]) || empty($extra["event"]) );
    }

}
