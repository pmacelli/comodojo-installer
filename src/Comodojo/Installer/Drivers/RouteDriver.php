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

class RouteDriver extends AbstractDriver {

    // This is an example of how the route definition inside the composer.json should be implemented
    //
    //  "extra": {
    //      "routes": {
    //          "test": {
    //              "query": [
    //                  {
    //                      "page": "p(\\d+)"
    //                  },
    //                  {
    //                      "ux_timestamp*": "\\d{10}",
    //                      "microseconds": "\\d{4}"
    //                  }
    //              ],
    //              "type": "ROUTE",
    //              "class": "\\Comodojo\\Dispatcher\\Service\\Test"
    //              "parameters": {
    //                  "cache": "SERVER",
    //                  "ttl": 3
    //              }
    //          }
    //      }
    //  }

    protected $base_route = [
        "query" => [],
        "type" => 'ROUTE',
        "class" => null,
        "parameters" => [],
        "package_name" => null
    ];

    public function install($package_name, $package_path, array $package_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Installing routes from package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->installRoutes($config, $package_name, $package_extra);

        $io->write("<info>>>> Saving routes configuration</info>");
        $persistence->dump($config);

    }

    public function update($package_name, $package_path, array $initial_extra, array $target_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Updating routes of package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->uninstallRoutes($config, $package_name, $initial_extra);
        $config = $this->installRoutes($config, $package_name, $target_extra);

        $io->write("<info>>>> Saving routes configuration</info>");
        $persistence->dump($config);

    }

    public function uninstall($package_name, $package_path, array $package_extra) {

        $io = $this->getIO();
        $persistence = $this->getPersistence();

        $io->write("<info>>>> Uninstalling routes of package ".$package_name."</info>");
        $config = $persistence->load();

        $config = $this->uninstallRoutes($config, $package_name, $package_extra);

        $io->write("<info>>>> Saving routes configuration</info>");
        $persistence->dump($config);

    }

    protected function installRoutes($config, $package_name, array $package_extra) {

        $io = $this->getIO();

        foreach ($package_extra as $route => $specs) {

            if ( array_key_exists($route, $config) ) {
                $io->write("<error>Duplicate route found!</error>");
                $io->write("<error>----------------------------</error>");
                $io->write("<error>Route $route in $package_name is already provided by ".$config[$route]["package_name"]."</error>");
                $io->write("<error>----------------------------</error>");
                continue;
            }

            if ( !self::validate($specs) ) {
                $io->write("<error>Invalid route definition in $package_name</error>");
                $io->write("<error>----------------------------</error>");
                $io->write("<error>$route => ".var_export($specs, true)."</error>");
                $io->write("<error>----------------------------</error>");
                continue;
            }

            $raw_route = ArrayOps::replaceStrict($this->base_route, $specs);
            $config[$route] = self::buildRoute($route, $raw_route, $package_name);
            $io->write(" <info>+</info> enabled route [$route]");

        }

        return $config;

    }

    protected function uninstallRoutes($config, $package_name, array $package_extra) {

        $io = $this->getIO();

        foreach ($package_extra as $route => $specs) {

            $exists = array_key_exists($route, $config);

            if ( $exists && $config[$route]["package_name"] == $package_name) {
                unset($config[$route]);
                $io->write(" <info>-</info> route [$route] removed");
            } else if ( $exists ) {
                $io->write("<error>Route [$route] does not belong to $package_name and cannot be removed</error>");
                $io->write("<error>----------------------------</error>");
                $io->write("<error>Found existing route $route that belongs to ".$config[$route]["package_name"]);
                $io->write("<error>----------------------------</error>");
            } else {
                $io->write("<warning>!</warning> Missing route [$route]");
            }

        }

        return $config;

    }

    protected static function buildRoute($route, $raw_route, $package_name) {

        $query = [$route];

        if (is_array($raw_route["query"])) {

            foreach ($raw_route["query"] as $param) {

                $query[] = json_encode($param);

            }

            $query = implode("/", $query);

        } else {

            $query[] = $raw_route["query"];

        }

        unset($raw_route["query"]);
        $raw_route["route"] = $query;
        $raw_route["package_name"] = $package_name;

        return $raw_route;

    }

    protected static function validate(array $extra) {
        return !empty($extra["type"]);
    }

}
