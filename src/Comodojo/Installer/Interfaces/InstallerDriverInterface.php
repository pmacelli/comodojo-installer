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

    public function __construct(Composer $composer, IOInterface $io, Configuration $configuration, InstallerParameters $parameters, InstallerPersistenceInterface $persistence);

    public function install($package_name, $package_path, array $package_extra);

    public function update($package_name, $package_path, array $initial_extra, array $target_extra);

    public function uninstall($package_name, $package_path, array $package_extra);

}
