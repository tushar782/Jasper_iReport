<?php
ob_start(); 
include "main.php";
// require_once('dbconnect.php');

// set_time_limit(100);
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/bank.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

// $conn = pg_connect("host=127.0.0.1 dbname=CBSLIVEDB user=postgres password=admin");

    $tmp=[
       
    ];
    $data[0]=$tmp;
    $i++;
    

ob_end_clean();

$config = ['driver'=>'array','data'=>$data];
// print_r($config);
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');
