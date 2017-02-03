<?php namespace Comodojo\Installer\Components;

use \Composer\Composer;
use \Composer\IO\IOInterface;
use \Comodojo\Foundation\Base\Configuration;

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

class InstallerDriverManager {

    protected $driver;

    protected $persistence;

    public function __construct(
        Composer $composer,
        IOInterface $io,
        Configuration $configuration,
        InstallerConfigurationExtraParser $extra
    ) {

        $driver = $extra->getDriver();
        $driver_source = $extra->getDriverSource();

        $persistence = $extra->getPersistence();
        $persistence_source = $extra->getPersistenceSource();

        $parameters = $extra->getParams();
        $base_path = realpath($composer->getConfig()->get('vendor-dir').'/../');

        if ( !class_exists($persistence) && $persistence_source !== null ) {
            include $base_path."/$persistence_source";
        }
        $this->persistence = new $persistence($composer, $io, $configuration, $parameters);

        if ( !class_exists($driver) && $driver_source !== null ) {
            include $base_path."/$driver_source";
        }
        $this->driver = new $driver($composer, $io, $configuration, $parameters, $this->persistence);

    }

    public function getDriver() {

        return $this->driver;

    }

    public function getPersistence() {

        return $this->persistence;

    }

}
