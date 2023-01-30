<?php

ob_start(); 
include "main.php";
require_once('dbconnect.php');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/PrematuredAccountCloseList.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

 $conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");


 //variables
$AC_NO = $_GET['AC_NO'];
$BRANCH = $_GET['BRANCH'];
$START_DATE = $_GET['START_DATE'];
$END_DATE = $_GET['END_DATE'];
$AC_ACNOTYPE = $_GET['AC_ACNOTYPE'];
$AC_TYPE = $_GET['AC_TYPE'];
// $bankName = $_GET['bankName'];
// $NAME = $_GET['NAME'];
// $scheme = $_GET['scheme'];      
// $branch = $_GET['branch'];
$dateformate = "'DD/MM/YYYY'";



 $query = 'SELECT DPMASTER."AC_MATUAMT",DPMASTER."id",NOMINEELINK."DPMasterID",COUNT(NOMINEELINK."id") As "NO_OF_NOMINEES", CAST(DPMASTER."AC_EXPDT" AS DATE), 
           DPMASTER."AC_ACNOTYPE" ,DPMASTER."AC_MATUAMT",DPMASTER."AC_EXPDT", DPMASTER."AC_TYPE", DPMASTER."AC_NO", 
           SCHEMAST."S_NAME",DPMASTER."AC_OPDATE", DPMASTER."AC_MONTHS", DPMASTER."AC_DAYS", NOMINEELINK."AC_NNAME" ,DPMASTER."AC_NAME"
           From DPMASTER 
           INNER JOIN NOMINEELINK ON DPMASTER."id" = NOMINEELINK."DPMasterID"
           INNER JOIN SCHEMAST ON DPMASTER."AC_TYPE" = SCHEMAST."id"
           WHERE DPMASTER."AC_ACNOTYPE" = '.$AC_ACNOTYPE.' 
           AND DPMASTER."AC_TYPE" ='.$AC_TYPE.'
            And DPMASTER."AC_NO" = '.$AC_NO.'
           GROUP BY DPMASTER."id",NOMINEELINK."DPMasterID",DPMASTER."AC_MATUAMT",NOMINEELINK."id", DPMASTER."AC_EXPDT", DPMASTER."AC_ACNOTYPE", 
           DPMASTER."AC_TYPE", DPMASTER."AC_NO", SCHEMAST."S_NAME",NOMINEELINK."AC_NNAME"';

        //echo $query; 

          
$sql =  pg_query($conn,$query);

$i = 0;
while($row = pg_fetch_assoc($sql))
{ 

     $tmp=[
      
        'AC_NAME' => $row['AC_NAME'],
        'AC_NNAME' => $row['AC_NNAME'],
        'AC_EXPDT' => $row['AC_EXPDT'],
        'AC_MATUAMT'=> $row['AC_MATUAMT'],
        'AC_OPDATE'=> $row['AC_OPDATE'],
        'S_NAME' => $row['S_NAME'],
        'AC_MONTHS' => $row['AC_MONTHS'],
        'AC_DAYS' => $row['AC_DAYS'],
         'BRANCH' => $BRANCH,
         'START_DATE' => $START_DATE,
         'END_DATE' => $END_DATE,
        'AC_ACNOTYPE' => $AC_ACNOTYPE,
        'AC_TYPE' => $AC_TYPE,
        'BRANCH' => $BRANCH,
        'AC_NO' => $AC_NO,
       // 'bankName'=> $bankName,
        // 'NAME' => $NAME,
        // 'BRANCH_CODE' => $BRANCH_CODE,
        // 'PRINT_DATE' => $PRINT_DATE,
       
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
    

