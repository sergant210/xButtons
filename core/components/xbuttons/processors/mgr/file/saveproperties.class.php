<?php

class xButtonsSavePropertiesProcessor extends modProcessor{
    public $permission = 'xbtn_save_file';

    public function checkPermissions() {
        return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
    }

    public function process() {
        $name = basename(trim($this->getProperty('name','')));
        $name = $this->modx->sanitizeString($name);
        if (empty($name)) {
            return $this->failure($this->modx->lexicon('xbuttons_err_file_nsp'));
        }
        $element = $this->getProperty('element','');
        $path = $this->modx->getOption('xbuttons_core_path', NULL, $this->modx->getOption('core_path') . 'components/xbuttons/')."elements/temp/";
        if (!is_dir($path) && !mkdir($path,0755)) $this->failure($this->modx->lexicon('xbuttons_err_path_nf'));
        /** @var modPlugin|modSnippet|modChunk|modTemplate $object */
        $object = null;
        switch ($element) {
            case 'plugins':
                $object = $this->modx->getObject('modPlugin',array('name'=>$name));
                break;
            case 'snippets':
                $object = $this->modx->getObject('modSnippet',array('name'=>$name));
                break;
            case 'chunks':
                $object = $this->modx->getObject('modChunk',array('name'=>$name));
                break;
            case 'templates':
                $object = $this->modx->getObject('modTemplate',array('templatename'=>$name));
                break;
        }
        if (!is_object($object)) return $this->failure($this->modx->lexicon('xbuttons_err_element_nf'));

        $properties = $object->get('properties');
        $data = array();
        foreach ($properties as $property) {
            $data[] = array(
                $property['name'],
                $property['desc'],
                !empty($property['type']) ? $property['type'] : 'textfield',
                !empty($property['options']) ? $property['options'] : array(),
                $property['value'],
                !empty($property['lexicon']) ? $property['lexicon'] : '',
                false, // overridden set to false
                $property['desc_trans'],
                !empty($property['area']) ? $property['area'] : '',
            );
        }


        $file = $path.'download_file.txt';
        file_put_contents($file, $this->modx->toJSON($data));
        $name = substr($element,0,strlen($element)-1).'.'.$name.'_prop.txt';
        $object = array('name'=>$name);
        return $this->success('',$object);
    }
}

return 'xButtonsSavePropertiesProcessor';