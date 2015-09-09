<?php

class xbuttonsLoadFromFileProcessor extends modProcessor{
    public $permission = 'xbtn_load_file';

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
        $file = trim($this->getProperty('file',''));
        $element = $this->getProperty('element');
        if (empty($file)) return $this->failure($this->modx->lexicon('xbuttons_err_file_ns'));
        $path = $this->modx->getOption('xbuttons_core_path', NULL, $this->modx->getOption('core_path') . 'components/xbuttons/').'elements/';
        $f = $path . $element .'/' . $file;
        if (file_exists($f)) {
            $code = @file_get_contents($f);
        } else {
            return $this->failure($this->modx->lexicon('xbuttons_err_file_nf'));
        }

        return $this->success($code);
    }
}

return 'xbuttonsLoadFromFileProcessor';