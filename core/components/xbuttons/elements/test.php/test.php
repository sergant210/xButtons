<?php
switch ($modx->event->name) {
    case 'OnSnipFormPrerender':
        $field = $modx->quote('modx-snippet-snippet');
        $element = $modx->quote('snippets');
        break;
    case 'OnTempFormPrerender':
        $field = $modx->quote('modx-template-content');
        $element = $modx->quote('templates');
        break;
    case 'OnChunkFormPrerender':
        $field = $modx->quote('modx-chunk-snippet');
        $element = $modx->quote('chunks');
        break;
    case 'OnPluginFormPrerender':
        $field = $modx->quote('modx-plugin-plugincode');
        $element = $modx->quote('plugins');
        break;
    default:
        return;
}
if (!empty($field)) {
    $modx->controller->addLexiconTopic('xbuttons:default');
    $modx->controller->addLastJavascript($modx->getOption('xbuttons_assets_url', null, $modx->getOption('assets_url') . 'components/xbuttons/').'js/mgr/xbuttons.js');
    $_html = "<script type=\"text/javascript\">\n";
    $_html .= "\tvar xButtons_config = {\n";
    $_html .= "\t\telement : {$element},\n" ;
    $_html .= "\t\tfield : {$field},\n" ;
    $_html .= "\t\tconnector_url : '". $modx->getOption('xbuttons_assets_url', null, $modx->getOption('assets_url') . "components/xbuttons/")."connector.php'\n";
    $_html .= "\t};\n";
    $_html .= "</script>";
    $modx->controller->addHtml($_html);
}