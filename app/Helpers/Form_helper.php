<?php
//displays validation errord
function display_error($validation, $field){
  if($validation->hasError($field)){
    return $validation->getError($field);
  }else{
    return false;
  }
}




?>
