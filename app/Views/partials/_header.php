<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>ZR|<?php echo isset($title)?$title:'Homepage'?></title>
    <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/datatables/datatables.min.css')?>">
    <script src="https://kit.fontawesome.com/f93179eec4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= base_url('assets/vendors/font-awsome/css/all.min.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css')?>">


    <?php if(isset($page_name) && ($page_name == 'report' || $page_name == 'classificators'  || $page_name == 'salary_fond' || $page_name == 'advance_month' )){ ?>
    <link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/jquery-ui.min.css')?>">
    <?php } ?>
    
    <script>
    var base_url = "<?php echo base_url();?>";
    </script>
  </head>
  <body>
