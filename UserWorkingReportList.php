<?php
ob_start(); 
include "main.php";
require_once('dbconnect.php');

set_time_limit(100);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/UserWorkingReportList.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

$conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");

// variables

 $BRANCH = $_GET['BRANCH'];
 $TRAN_DATE = $_GET['TRAN_DATE'];


 $schm = "'CS'";
 $schm = "'TR'";
 $schm = "'CL'";

$query = '';

          //echo $query;
          
$sql =  pg_query($conn,$query);

 $i = 0;


while($row = pg_fetch_assoc($sql))
{ 
    

    $tmp=[
       
        'END_DATE' => $END_DATE,
        'schm' => $schm,
        'BRANCH' => $BRANCH,
        'TRAN_DATE' => $TRAN_DATE,
        
    ];
    $data[$i]=$tmp;
    $i++;  
}
ob_end_clean();
// echo $query;

$config = ['driver'=>'array','data'=>$data];
// echo $filename;
// print_r($data)
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');
    
?>