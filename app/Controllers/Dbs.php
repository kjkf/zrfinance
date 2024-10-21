<?php

namespace App\Controllers;

use App\Models\MaterialsModel;

class Dbs extends BaseController
{
    public function index()
    {
       $db1 = db_connect();
       $model = new MaterialsModel($db1);

       $db2 = db_connect("db_class", true, true);
       $model2 = new MaterialsModel($db2);

       // echo '<pre>';
       // print_r($model->getAllUnits());
       // echo '<pre><hr>';

       echo '<pre>';
       print_r($model2->getFromAnotherDB());
       echo '<pre><hr>';
    }
}
