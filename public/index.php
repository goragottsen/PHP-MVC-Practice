<?php
//receives all the requests & loads the proper file associated with the action
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));
const DS = DIRECTORY_SEPARATOR;

require APPLICATION_PATH . DS . 'config' . DS . 'config.php';

$page = get('page', 'home');
$model = $config['MODEL_PATH'] . $page . '.php';
$view = $config['VIEW_PATH'] . $page . '.phtml';
$error = $config['VIEW_PATH'] . 'error.phtml';
if(file_exists($model)){
    require $model;
}
$main_content = $error;
if(file_exists($view)){
    $main_content = $view;
}

include $config['VIEW_PATH'] . 'layout.phtml';
include $config['VIEW_PATH'] . 'scripts.phtml';

?>
<a href="/folder_view/vs.php?s=<?php echo __FILE__?>" target="_blank">View Source</a>
