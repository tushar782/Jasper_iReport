<?php

ob_start(); 
include "main.php";
require_once('dbconnect.php');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/ShareIssueRegister.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

 $conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");


 //variables
// $PRINT_DATE = $_GET['PRINT_DATE'];
$START_DATE = $_GET['START_DATE'];
$END_DATE = $_GET['END_DATE'];
// $AC_ACNOTYPE = $_GET['AC_ACNOTYPE'];
// $AC_TYPE = $_GET['AC_TYPE'];
// $scheme = $_GET['scheme'];      
// $branch = $_GET['branch'];
$dateformate = "'DD/MM/YYYY'";



 $query = ' ';

           // echo $query; 

          
$sql =  pg_query($conn,$query);

$i = 0;
while($row = pg_fetch_assoc($sql))
{ 

     $tmp=[
        // 'AC_NAME' => $row['AC_NAME'],
        // 'MARGIN' => $row['MARGIN'],
        // 'RATE' => $row['RATE'],
        // 'AC_SECURITY_AMT' => $row['AC_SECURITY_AMT'],
        // 'ac_sanction_amount' => $row['ac_sanction_amount'],
        // 'TOTAL_WEIGHT_GMS' => $row['TOTAL_WEIGHT_GMS'],
        // 'CLEAR_WEIGHT_GMS' => $row['CLEAR_WEIGHT_GMS'],
        // 'NOMINEE_RELATION' => $row['NOMINEE_RELATION'],
        // 'ARTICLE_NAME' => $row['ARTICLE_NAME'],
        // 'SUBMISSION_DATE' => $row['SUBMISSION_DATE'],
        // 'NOMINEE_RELATION' => $row['NOMINEE_RELATION'],
        // 'ARTICLE_NAME' => $row['ARTICLE_NAME'],
        // 'TOTAL_WEIGHT_GMS' => $row['TOTAL_WEIGHT_GMS'],
        // 'CLEAR_WEIGHT_GMS' => $row['CLEAR_WEIGHT_GMS'],
        // 'RATE' => $row['RATE'],
        'START_DATE' => $START_DATE,
        'END_DATE' => $END_DATE,
        'BRANCH'  => $BRANCH,
        // 'AC_TYPE' => $AC_TYPE,
        // 'AC_ACNOTYPE' => $AC_ACNOTYPE,
        
       
     ];
    
    $data[$i]=$tmp;
    $i++;
    
}
ob_end_clean();

$config = ['driver'=>'array','data'=>$data];
//print_r($data);
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');

?> 