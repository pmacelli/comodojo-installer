<?php namespace Comodojo\Installer\Components;

use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \Composer\Util\Filesystem as ComposerFilesystem;

/**
 * Extend the Composer Filesystem to make recursive copies
 *
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

class Filesystem extends ComposerFilesystem {

    public function rcopy($source, $target) {

        if (!is_dir($source)) {

            copy($source, $target);

            return;

        }

        $it = new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS);

        $ri = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);

        $this->ensureDirectoryExists($target);

        foreach ($ri as $file) {

            $targetPath = $target . DIRECTORY_SEPARATOR . $ri->getSubPathName();

            if ($file->isDir()) {

                $this->ensureDirectoryExists($targetPath);

            } else {

                copy($file->getPathname(), $targetPath);

            }

        }

    }

}
