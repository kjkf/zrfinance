<?php
//displays validation errord
if (!function_exists('display_error')) {
  function display_error($validation, $field)
  {
    if ($validation->hasError($field)) {
      return $validation->getError($field);
    } else {
      return false;
    }
  }
}
if (!function_exists('word_form')) {
  function word_form($word, $count)
  {
    if ($word == "сотрудник") {
      return employee_form($count);
    }
  }
}
if (!function_exists('employee_form')) {
  function employee_form($count)
  {
    $res = "";
    $i = substr($count, -1);
    switch ($i) {
      case 1:
        $res = $count . " сотрудник";
        break;
      case 2:
      case 3:
      case 4:
        $res = $count . " сотрудника";
        break;
      default:
        $res = $count . " сотрудников";
    }
    return $res;
  }
}

if (!function_exists('getMonthArrayRu')) {
  function getMonthArrayRu()
  {
    return   ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
  }
}

if (!function_exists('getMonthByNum')) {
  function getMonthByNum($month)
  {
    $monthes = getMonthArrayRu();

    return $monthes[$month];
  }
}
