<?php namespace Comodojo\Installer\Persistence;

use \Symfony\Component\Yaml\Yaml;
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

class YamlPersistence extends AbstractPersistence {

    const DEFAULT_DEPTH = 4;

    public function load() {

        $name = $this->getParameters()->get('config-file');

        if ( $name === null ) throw new InstallerException("No config-file name specified");

        if ( file_exists($name) && is_readable($name) ) return Yaml::parse(file_get_contents($name));

        return [];

    }

    public function dump(array $data) {

        $name = $this->getParameters()->get('config-file');

        if ( $name === null ) throw new InstallerException("No config-file name specified");

        $config_depth = $this->getParameters()->get('depth');

        $depth = $config_depth === null ? self::DEFAULT_DEPTH : intval($config_depth);

        $filedata = Yaml::dump($data, $depth);

        if (
            ( file_exists($name) && is_writable($name) ) ||
            is_writeable(pathinfo($name, PATHINFO_DIRNAME))
        ) return file_put_contents($name, $filedata, LOCK_EX);

        throw new InstallerException("Cannot write to configuration file $name");

    }

}
