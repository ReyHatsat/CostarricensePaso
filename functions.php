<?php

function _title($title = 'Inicio'){
  if (isset($_GET['p'])) {
    $title = ucwords(implode(' ', explode('_', $_GET['p'])));
  }
  $title =  APP_NAME.' - '.$title;
  echo "<title>$title</title>";
}


function _component($cmp, $ext = '.php'){
  if (file_exists(PATH_COMPONENTS.$cmp.$ext)) {
    include(PATH_COMPONENTS.$cmp.$ext);
  }else{
    echo "Component '$cmp$ext' does not exist or is not located in the components folder.";
  }
}


function _pageContent(){
  if (isset($_GET['p']) && file_exists(PATH_PAGES.$_GET['p'].'/'.$_GET['p'].'.php')) {
    include(PATH_PAGES.$_GET['p'].'/'.$_GET['p'].'.php');
  }else{
    include(PATH_PAGES.DEFAULT_PAGE.'/'.DEFAULT_PAGE.'.php');
  }
}



function _pageScript(){
  if (isset($_GET['p']) && file_exists(PATH_PAGES.$_GET['p'].'/'.$_GET['p'].SCRIPT_EXT)) {
    echo "<script>";
      include(PATH_PAGES.$_GET['p'].'/'.$_GET['p'].SCRIPT_EXT);
    echo "</script>";
  }else {
    echo "<script>";
      include(PATH_PAGES.DEFAULT_PAGE.'/'.DEFAULT_PAGE.SCRIPT_EXT);
    echo "</script>";
  }
}



function _pageScriptAdm(){
  if (isset($_GET['adm']) && file_exists(PATH_ADMIN.$_GET['adm'].'/'.$_GET['adm'].SCRIPT_EXT) && !is_null($_SESSION[SES_OBJ][ADM_VALID])) {
    echo "<script>";
      include(PATH_ADMIN.$_GET['adm'].'/'.$_GET['adm'].SCRIPT_EXT);
    echo "</script>";
  }else {
    echo "<script>";
      include(PATH_ADMIN.DEFAULT_PAGE.'/'.DEFAULT_PAGE.SCRIPT_EXT);
    echo "</script>";
  }
}





function _pageContentAdm(){
  if(isset($_GET['adm']) && file_exists(PATH_ADMIN.$_GET['adm'].'/'.$_GET['adm'].'.php') && !is_null($_SESSION[SES_OBJ][ADM_VALID])){
    include(PATH_ADMIN.$_GET['adm'].'/'.$_GET['adm'].'.php');
  }else{
    include(PATH_ADMIN.DEFAULT_PAGE.'/'.DEFAULT_PAGE.'.php');
  }
}




$asyncLoad = [];
function _prepareAsyncExecute($execute){
  $asyncLoad[] = $execute;
}

function _asyncExecute(){
  foreach ($GLOBALS['asyncLoad'] as $execute) {
    $execute();
  }
}




function _session(){
  return (isset($_SESSION[SES_OBJ]) && !empty($_SESSION[SES_OBJ]));
}

function _loggedIn(){
  if (!validatePage()) {
    echo "<script>location.replace('?p=login&pending')</script>";
  }
}




?>
