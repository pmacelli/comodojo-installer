<?php namespace Comodojo\Installer\Persistence;

use \Comodojo\Installer\Interfaces\InstallerPersistenceInterface;
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

abstract class AbstractAction implements InstallerPersistenceInterface {

    protected $composer;

    protected $io;

    protected $configuration;

    protected $parameters;

    public function __construct(Composer $composer, IOInterface $io, Configuration $configuration, InstallerParameters $parameters) {

        $this->composer = $composer;
        $this->io = $io;
        $this->configuration = $configuration;
        $this->parameters = $parameters;

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

    // configuration can change from time to time...
    public function setConfiguration(Configuration $configuration) {

        $this->configuration = $configuration;

        return $this;

    }

    public function getParameters() {

        return $this->parameters;

    }

    abstract public function load();

    abstract public function dump(array $data);

}
