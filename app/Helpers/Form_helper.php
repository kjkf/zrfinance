<?php
//displays validation errord
function display_error($validation, $field){
  if($validation->hasError($field)){
    return $validation->getError($field);
  }else{
    return false;
  }
}

function word_form($word, $count) {
  if ($word == "сотрудник") {
    return employee_form($count);
  }
}
function employee_form($count) {
  $res = "";
  $i = substr($count, -1);
  switch ($i) {
    case 1:
        $res = $count." сотрудник";
        break;
    case 2:
    case 3:
    case 4:
        $res = $count." сотрудника";
        break;
    default:
      $res = $count." сотрудников";
  }
  return $res;
}

function getMonthArrayRu() {
  return   ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
  
}

function getMonthByNum($month) {
  $monthes = getMonthArrayRu();

  return $monthes[$month];
}

?>
