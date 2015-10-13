<?php

class xButtonsSaveToFileProcessor extends modProcessor{
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
        $name = basename(trim($this->getProperty('name','')));
        $name = $this->modx->sanitizeString($name);
        if (empty($name)) {
            $this->addFieldError('name',$this->modx->lexicon('xbuttons_err_file_nsp'));
            return $this->failure($this->modx->lexicon('xbuttons_err_file_nsp'));
        }
        $element = $this->getProperty('element','');
        $overwrite = $this->getProperty('overwrite',false);
        $path = $this->modx->getOption('xbuttons_files_path', NULL, $this->modx->getOption('core_path') . 'components/xbuttons/elements/')."{$element}/";
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
        $file = $path.$name.$ext;
        if (file_exists($file) && !$overwrite) return $this->failure($this->modx->lexicon('xbuttons_err_file_ae'));
        if (!empty($code)) {
            file_put_contents($file, $code);
        } else {
            unlink($file);
        }

        return $this->success('');
    }
}

return 'xButtonsSaveToFileProcessor';