<?php

class xButtonsSaveTmpFileProcessor extends modProcessor{
    public $permission = 'xbtn_save_file';

    /**
     * {@inheritdoc}
     * @return bool
     */
    public function checkPermissions() {
        return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
    }

    /**
     * {@inheritdoc}
     * @return array|string
     */
    public function process() {
        $code = trim($this->getProperty('code',''));
        $prop = $this->getProperty('prop',false);
        $name = basename(trim($this->getProperty('name','')));
        $name = $this->modx->sanitizeString($name);
        if (empty($name)) {
            return $this->failure($this->modx->lexicon('xbuttons_err_file_nsp'));
        }
        $name = str_replace(' ','_',$name);
        $element = $this->getProperty('element','');
        $path = $this->modx->getOption('xbuttons_core_path', NULL, $this->modx->getOption('core_path') . 'components/xbuttons/')."elements/temp/";
        if (!is_dir($path) && !mkdir($path,0755)) $this->failure($this->modx->lexicon('xbuttons_err_path_nf'));
        $ext = '';
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
        $file = $path.'download_file.txt';
        file_put_contents($file, $code);
        $object = array('name'=>$name.$ext);
        return $this->success('',$object);
    }
}

return 'xButtonsSaveTmpFileProcessor';