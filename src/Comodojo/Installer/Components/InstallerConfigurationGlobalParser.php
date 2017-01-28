<?php namespace Comodojo\Installer\Components;

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

class InstallerConfigurationGlobalParser {

    const DEFAULT_EXTRA_FIELD = "comodojo-configuration";

    const DEFAULT_PERSISTENCE = "\\Comodojo\\Installer\\Persistence\\YamlPersistence";

    const DEFAULT_CONFIG_FILE = "config/comodojo-configuration.yml";

    const DEFAULT_CONFIG_DEPTH = 6;

    protected $properties;

    protected $parameters;

    // "global-config": {
    //     "extra-field": "comodojo-configuration",
    //     "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
    //     "params": {
    //         "config-file": "config/comodojo-configuration.yml",
    //         "depth": 6
    //     }
    // },

    public function __construct(array $parameters = []) {

        $default_properties = [
            'extra-field' => self::DEFAULT_EXTRA_FIELD,
            'persistence' => self::DEFAULT_PERSISTENCE
        ];

        $this->properties = ArrayOps::replaceStrict($default_properties, $parameters);

        $default_parameters = [
            "config-file" => self::DEFAULT_CONFIG_FILE,
            "depth" => self::DEFAULT_CONFIG_DEPTH
        ];

        if ( isset($parameters['params']) && is_array($parameters['params']) ) {
            $default_parameters = array_replace($default_parameters, $parameters['params']);
        }

        $this->parameters = new InstallerParameters($default_parameters);

    }

    public function getExtraField() {

        return $this->properties['extra-field'];

    }

    public function getPersistence() {

        return $this->properties['persistence'];

    }
    public function getParams() {

        return $this->parameters;

    }

}
