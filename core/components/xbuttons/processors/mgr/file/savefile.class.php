<?php

class xButtonsSaveToFileProcessor extends modProcessor{
    public function process() {
        $code = trim($this->getProperty('code',''));
        $file = trim($this->getProperty('file',''));
        $element = $this->getProperty('element','');
        $path = $this->modx->getOption('xbuttons_core_path', NULL, $this->modx->getOption('core_path') . 'components/xbuttons/')."elements/{$element}/";
        if (!is_dir($path) && !mkdir($path,0755)) $this->failure($this->modx->lexicon('xbuttons_err_path_nf'));
        switch ($element) {
            case 'plugins':
            case 'snippets':
                $ext = '.php';
                break;
            case 'chunks':
            case 'templates':
                $ext = '.html';
                break;
        }
        if (!empty($code) && $file) file_put_contents($path.$file.$ext, $code );

        return $this->success('');
    }
}

return 'xButtonsSaveToFileProcessor';