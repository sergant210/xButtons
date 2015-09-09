<?php

class elementGetFilesProcessor extends modProcessor{
    public function process() {
        $element = $this->getProperty('element');
        $prop = $this->getProperty('prop',false);
        $path = $this->modx->getOption('xbuttons_core_path', NULL, $this->modx->getOption('core_path') . 'components/xbuttons/').'elements/'.$element.'/';
        $files = array();
        foreach(scandir($path) as $tmp) {
            if ($tmp =='.' || $tmp =='..' || ($prop && !preg_match('/\.txt$/',$tmp)) || (!$prop && preg_match('/\.txt$/',$tmp))) continue;
            $files[] = $tmp;
        };
        return $this->success($files);
    }
}

return 'elementGetFilesProcessor';