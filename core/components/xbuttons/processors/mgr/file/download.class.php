<?php

/**
 * Download a file
 */
class xButtonsDownloadFileProcessor extends modProcessor {
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
     */
    public function process() {

        $name = $this->getProperty('filename','');
        if (empty($name)) {
            return $this->failure($this->modx->lexicon('xbuttons_err_file_nsp'));
        }

        $path = $this->modx->getOption('xbuttons_core_path', NULL, $this->modx->getOption('core_path') . 'components/xbuttons/')."elements/temp/";
        $file = $path.'download_file.txt';

        if (!file_exists($file)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[xButtons] '.$this->failure($this->modx->lexicon('xbuttons_err_file_nf')));
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($file));
        header('Content-Disposition: attachment; filename='.$name);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_get_level() && @ob_end_flush();
        @readfile($file);
        die();
    }
}

return 'xButtonsDownloadFileProcessor';