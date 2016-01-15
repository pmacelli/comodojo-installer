<?php namespace Comodojo\Installer\Configuration;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use \Composer\Util\Filesystem as ComposerFilesystem;

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