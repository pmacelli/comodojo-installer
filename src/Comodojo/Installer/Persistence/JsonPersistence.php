<?php namespace Comodojo\Installer\Persistence;

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

class JsonPersistence extends AbstractAction {

    public function load() {

        $name = $this->getParameters()->get('config-file');

        if ( $name === null ) throw new InstallerException("No config-file name specified");

        if ( file_exists($name) && is_readable($name) ) return json_decode(file_get_contents($name), true);

        return [];

    }

    public function dump(array $data) {

        $name = $this->getParameters()->get('config-file');

        if ( $name === null ) throw new InstallerException("No config-file name specified");

        $filedata = json_encode($data);

        if (
            ( file_exists($name) && is_writable($name) ) ||
            is_writeable(pathinfo($file, PATHINFO_DIRNAME))
        ) return file_put_contents($name, $filedata, LOCK_EX);

        throw new InstallerException("Cannot write to configuration file $name");

    }

}
