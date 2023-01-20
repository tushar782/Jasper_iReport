<?php
ob_start(); 
include "main.php";
//require_once('dbconnect.php');

set_time_limit(100);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/MinorToMajor.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

$conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");

// variables
 $AC_EXPDT = $_GET['AC_EXPDT'];
 $ac_type = $_GET['ac_type'];
 $sdate = $_GET['sdate'];
 $edate = $_GET['edate'];
 $ac_acnotype = $_GET['ac_acnotype'];
 $branch_name = $_GET['branch_name'];
 

$ac_type ="'8'";
$ac_acnotype = "'SB'";
$dateformat ="'YYYY/MM/DD'";


$query =' SELECT DPMASTER."AC_ACNOTYPE", DPMASTER."AC_TYPE", DPMASTER."AC_NO", DPMASTER."AC_NAME", DPMASTER."AC_MBDATE", DPMASTER."AC_GRDNAME" ,DPMASTER."AC_EXPDT",
          DPMASTER."AC_GRDRELE", SCHEMAST."S_NAME", (CASE WHEN "AC_MBDATE" IS NULL THEN null ELSE
          (select add_months(DPMASTER."AC_EXPDT",(18*12))) END ) as months ,
          age(CAST(DPMASTER."AC_OPDATE" AS date), CAST(DPMASTER."AC_CLOSEDT" AS date) ) AS "AGE"
          FROM DPMASTER , SCHEMAST WHERE DPMASTER."AC_ACNOTYPE" = SCHEMAST."S_ACNOTYPE"
          AND DPMASTER."AC_TYPE" = SCHEMAST.ID AND CAST(DPMASTER."AC_MINOR" AS integer) <> 0 
          AND DPMASTER."AC_ACNOTYPE" ='.$ac_acnotype.'
           AND DPMASTER."AC_TYPE" = '.$ac_type.' 
          AND (CAST( DPMASTER."AC_OPDATE" AS date) IS NULL OR CAST(DPMASTER."AC_OPDATE" AS DATE) <= CAST('.$sdate.' AS DATE ))
          AND (CAST( DPMASTER."AC_CLOSEDT" AS date) IS NULL OR CAST(DPMASTER."AC_CLOSEDT" AS DATE) >=CAST('.$edate.' AS DATE))';

        //   echo $query;
          
$sql =  pg_query($conn,$query);

 $i = 0;


while($row = pg_fetch_assoc($sql))
{ 
    
    $tmp=[
        
        // 'AC_EXPDT' => $row['EXPDT'],
        'AC_NAME'=> $row['AC_NAME'],
        'AC_GRDNAME' => $row['AC_GRDNAME'],
        'AC_GRDRELE' => $row['AC_GRDRELE'],
        'AC_MBDATE' => $row['AC_MBDATE'],
        'AGE' => $row['AGE'],
        'S_NAME' => $row['S_NAME'],
        'AC_NO'=> $row['AC_NO'],
        'ac_type' => $ac_type,
        'sdate' => $sdate,
        'edate' => $edate , 
        // 'print_date'=>  $print_date,
        'AC_EXPDT' => $AC_EXPDT,
        'ac_acnotype' => $ac_acnotype,
        'branch_name' => $branch_name,
        // 'schemecode'=> $schemecode,
    ];
    $data[$i]=$tmp;
    $i++;  
}
// ob_end_clean();
// // echo $query;

$config = ['driver'=>'array','data'=>$data];
// echo $filename;
 //print_r($data)
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');
    
?>