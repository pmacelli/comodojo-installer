<?php namespace Comodojo\Installer\Interfaces;

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

interface InstallerDriverInterface {

    /**
     * Driver constructor,
     * just to ensure all pieces are in the right place
     *
     * @param Composer $composer
     * @param IOInterface $io
     * @param Configuration $configuration
     * @param InstallerParameters $parameters
     * @param InstallerPersistenceInterface $persistence
     */
    public function __construct(
        Composer $composer,
        IOInterface $io,
        Configuration $configuration,
        InstallerParameters $parameters,
        InstallerPersistenceInterface $persistence
    );

    /**
     * Install a package, processing all extra fields that it contains.
     *
     * @param string $package_name
     * @param string $package_path
     * @param array $package_extra
     *
     * @return void
     */
    public function install($package_name, $package_path, array $package_extra);

    /**
     * Update a package checking differences from it's initial and target extra fields.
     *
     * @param string $package_name
     * @param string $package_path
     * @param array $initial_extra
     * @param array $target_extra
     *
     * @return void
     */
    public function update($package_name, $package_path, array $initial_extra, array $target_extra);

    /**
     * Uninstall package, removing all related extra fields.
     *
     * @param string $package_name
     * @param string $package_path
     * @param array $package_extra
     *
     * @return void
     */
    public function uninstall($package_name, $package_path, array $package_extra);

}
