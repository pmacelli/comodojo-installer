<?php namespace Comodojo\Installer\Drivers;

use \Comodojo\Installer\Interfaces\InstallerDriverInterface;
use \Composer\Composer;
use \Composer\IO\IOInterface;
use \Comodojo\Foundation\Base\Configuration;
use \Comodojo\Installer\Components\InstallerParameters;
use \Comodojo\Installer\Interfaces\InstallerPersistenceInterface;

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

abstract class AbstractDriver implements InstallerDriverInterface {

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var InstallerParameters
     */
    protected $parameters;

    /**
     * @var InstallerPersistenceInterface
     */
    protected $persistence;

    /**
     * {@inheritDoc}
     */
    public function __construct(
        Composer $composer,
        IOInterface $io,
        Configuration $configuration,
        InstallerParameters $parameters,
        InstallerPersistenceInterface $persistence
    ) {

        $this->composer = $composer;
        $this->io = $io;
        $this->configuration = $configuration;
        $this->parameters = $parameters;
        $this->persistence = $persistence;

    }

    /**
     * @return Composer
     */
    public function getComposer() {

        return $this->composer;

    }

    /**
     * @return IOInterface
     */
    public function getIo() {

        return $this->io;

    }

    /**
     * @return Configuration
     */
    public function getConfiguration() {

        return $this->configuration;

    }

    /**
     * @return InstallerParameters
     */
    public function getParameters() {

        return $this->parameters;

    }

    /**
     * @return InstallerPersistenceInterface
     */
    public function getPersistence() {

        return $this->persistence;

    }

    /**
     * {@inheritDoc}
     */
    abstract public function install($package_name, $package_path, array $package_extra);

    /**
     * {@inheritDoc}
     */
    abstract public function update($package_name, $package_path, array $initial_extra, array $target_extra);

    /**
     * {@inheritDoc}
     */
    abstract public function uninstall($package_name, $package_path, array $package_extra);

}
