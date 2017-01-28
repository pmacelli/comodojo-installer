<?php namespace Comodojo\Installer\Components;

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

class InstallerConfiguration {

    protected $package_types = ["comodojo-bundle"];

    protected $global_config;

    protected $package_extra = [];

    protected $post_install_script;

    // "package-types": [
    //     "comodojo-bundle"
    // ],
    // "global-config": {
    //     "extra-field": "comodojo-configuration",
    //     "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
    //     "params": {
    //         "config-file": "config/comodojo-configuration.yml",
    //         "depth": 6
    //     }
    // },
    // "package-extra": {
    //     "routes": {
    //         "driver": "\\Comodojo\\Installer\\Drivers\\RouteDriver",
    //         "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
    //         "params": {
    //             "config-file": "config/comodojo-routes.yml"
    //         }
    //     },
    //     "plugins": {
    //         "driver": "\\Comodojo\\Installer\\Drivers\\PluginDriver",
    //         "persistence": "\\Comodojo\\Installer\\Persistence\\YamlPersistence",
    //         "params": {
    //             "config-file": "config/comodojo-plugins.yml"
    //         }
    //     }
    // },
    // "post-installer-script": "\\My\\Script"

    public function __construct(array $parameters = []) {

        if ( isset($parameters['package-types']) ) $this->package_types = $parameters['package-types'];

        $this->global_config = new InstallerConfigurationGlobalParser($parameters);

        if ( isset($parameters['package-extra']) && is_array($parameters['package-extra']) ) {
            foreach ($parameters['package-extra'] as $name => $content) {
                $this->package_extra[$name] = new InstallerConfigurationExtraParser($name, $content);
            }
        }

        if ( isset($parameters['post-installer-script']) ) $this->post_install_script = $parameters['post-installer-script'];

    }

    public function getPackageTypes() {

        return $this->package_types;

    }

    public function getGlobalConfig() {

        return $this->global_config;

    }
    public function getPackageExtra() {

        return $this->package_extra;

    }

    public function getPostInstallScript() {

        return $this->post_install_script;

    }

}
