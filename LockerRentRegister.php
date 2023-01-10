<?php
ob_start(); 
include "main.php";
require_once('dbconnect.php');

set_time_limit(100);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/LockerRentRegister.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

$conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");

// variables

 $ac_type = $_GET['ac_type'];
 $sdate = $_GET['sdate'];
 $edate = $_GET['edate'];
 $FLAG1 = $_GET['FLAG1'];
 $FLAG2 = $_GET['FLAG2'];

$schemecode = "'SB'";
$ac_type ="'8'";
$dateformat ="'DD/MM/YYYY'";



if(  $FLAG1 == 1 )
{
    $query = 'SELECT "AC_ACNOTYPE",'.$ac_type.', "AC_NO", "AC_NAME", "TRAN_AMOUNT","RENT_UPTO_DATE" 
               FROM DPMASTER, LOCKERRENTTRAN 
               WHERE DPMASTER."AC_ACNOTYPE" = LOCKERRENTTRAN."TRAN_ACNOTYPE" 
               AND DPMASTER."AC_TYPE" = CAST(LOCKERRENTTRAN."TRAN_ACTYPE" AS integer) 
               AND DPMASTER."AC_NO" = LOCKERRENTTRAN."TRAN_ACNO" 
               AND DPMASTER."AC_ACNOTYPE" = '.$schemecode.'
               AND DPMASTER."AC_TYPE" = '.$ac_type.'
               AND CAST("RENT_UPTO_DATE" AS DATE) BETWEEN DATE('.$sdate.') AND DATE('.$edate.')';
}
else
{
    $query = '(SELECT "AC_ACNOTYPE", '.$ac_type.', "AC_NO", "AC_NAME", "RENT_UPTO_DATE" 
             FROM DPMASTER LEFT OUTER JOIN 
              ( SELECT "TRAN_ACNOTYPE", "TRAN_ACTYPE", "TRAN_ACNO", 
                 MAX("RENT_UPTO_DATE") "RENT_UPTO_DATE" FROM LOCKERRENTTRAN 
                 GROUP BY "TRAN_ACNOTYPE", "TRAN_ACTYPE", "TRAN_ACNO") LOCKERRENTTRAN 
                 ON DPMASTER."AC_ACNOTYPE" = LOCKERRENTTRAN."TRAN_ACNOTYPE" 
                 AND DPMASTER."AC_TYPE" = CAST(LOCKERRENTTRAN."TRAN_ACTYPE" AS integer) 
                 AND DPMASTER."AC_NO" = LOCKERRENTTRAN."TRAN_ACNO" 
                 WHERE DPMASTER."AC_ACNOTYPE" = '.$schemecode.'
                 AND DPMASTER."AC_TYPE" = '.$ac_type.'
                 AND ("RENT_UPTO_DATE" IS NULL OR CAST('.$sdate.' AS date) < date('.$edate.'))
                 AND DPMASTER."AC_CLOSEDT" IS NULL)';

                //  echo $query;
}
             
$sql =  pg_query($conn,$query);

 $i = 0;


while($row = pg_fetch_assoc($sql))
{ 
    
    $tmp=[
        'AC_NO'=> $row['AC_NO'],
        'AC_ACNOTYPE'=> $row['AC_ACNOTYPE'],
        'AC_NAME'=> $row['AC_NAME'],
        'TRAN_AMOUNT'=> $row['TRAN_AMOUNT'],
        'RENT_UPTO_DATE'=> $row['RENT_UPTO_DATE'],
        'TRAN_ACNO'=> $row['TRAN_ACNO'],
        'TRAN_ACNOTYPE'=> $row['TRAN_ACNOTYPE'],
        'TRAN_TYPE'=> $row['TRAN_TYPE'],
        'TRAN_ACTYPE'=> $row['TRAN_ACTYPE'],
        'ac_type' => '$ac_type',
        'sdate' => $sdate,
        'edate' => $edate ,
        'BRANCH_NAME'=> $BRANCH_NAME,
        'FLAG1' => $FLAG1,
        'FLAG2' => $FLAG2, 
        // 'print_date'=>  $print_date,
    ];
    $data[$i]=$tmp;
    $i++;  
}
ob_end_clean();
// echo $query;

$config = ['driver'=>'array','data'=>$data];
// echo $filename;
//print_r($data);
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');
    
?>