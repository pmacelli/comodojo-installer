<?php namespace Comodojo\Installer\Drivers;

use \Comodojo\Installer\Interfaces\InstallerDriverInterface;
use \Comodojo\Installer\Components\InstallerParameters;
use \Comodojo\Foundation\Base\Configuration;
use \Composer\Composer;
use \Composer\IO\IOInterface;

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

abstract class AbstractDrivers implements InstallerDriverInterface {

    protected $composer;

    protected $io;

    protected $configuration;

    protected $parameters;

    protected $persistence;

    public function __construct(Composer $composer, IOInterface $io, Configuration $configuration, InstallerParameters $parameters, InstallerPersistenceInterface $persistence) {

        $this->composer = $composer;
        $this->io = $io;
        $this->configuration = $configuration;
        $this->parameters = $parameters;
        $this->persistence = $persistence;

    }

    public function getComposer() {

        return $this->composer;

    }

    public function getIo() {

        return $this->io;

    }

    public function getConfiguration() {

        return $this->configuration;

    }

    public function getParameters() {

        return $this->parameters;

    }

    public function getPersistence() {

        return $this->persistence;

    }

    abstract public function install($package_name, $package_path, array $package_extra);

    abstract public function update($package_name, $package_path, array $initial_extra, array $target_extra);

    abstract public function uninstall($package_name, $package_path, array $package_extra);

}
