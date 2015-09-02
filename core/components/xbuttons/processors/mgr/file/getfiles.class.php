<?php
class elementGetFilesProcessor extends modProcessor{
    public function process() {
        $element = $this->getProperty('element');
        $path = $this->modx->getOption('xbuttons_core_path', NULL, $this->modx->getOption('core_path') . 'components/xbuttons/').'elements/'.$element.'/';
        $files = array();
        foreach(scandir($path) as $tmp) {
            if ($tmp =='.' || $tmp =='..') continue;
            $files[] = $tmp;
        };
        return $this->success($files);
        //return count($files) > 0 ? $this->success($files) : $this->failure($this->modx->lexicon('xbuttons_err_files_nf'));
    }
}

return 'elementGetFilesProcessor';