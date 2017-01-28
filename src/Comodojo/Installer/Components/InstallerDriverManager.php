<?php namespace Comodojo\Installer\Components;

use \Comodojo\Foundation\Utils\ArrayOps;
use \Comodojo\Exception\InstallerException;

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

        $driver = $extra->getPersistence();
        $persistence = $extra->getPersistence();
        $parameters = $extra->getParams();

        $this->persistence = new $persistence($composer, $io, $configuration, $parameters);
        $this->driver = new $driver($composer, $io, $configuration, $parameters, $this->persistence);

    }

    public function getDriver() {

        return $this->driver;

    }

    public function getPersistence() {

        return $this->persistence;

    }

}
