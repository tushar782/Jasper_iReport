<?php
ob_start(); 
include "main.php";
//require_once('dbconnect.php');

set_time_limit(100);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/deposit.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

$conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");

// variables
 $print_date = $_GET['print_date'];
 $AC_TYPE = $_GET['AC_TYPE'];
 $sdate = $_GET['sdate'];
 $edate = $_GET['edate'];
 $AC_ACNOTYPE = $_GET['AC_ACNOTYPE'];
 

$schemecode = "'SB'";
$dateformat ="'DD/MM/YYYY'";
$ac_type ="'8'";

$query =' SELECT DPMASTER."AC_ACNOTYPE", DPMASTER."AC_TYPE", DPMASTER."AC_NO", DPMASTER."AC_NAME", DPMASTER."AC_MBDATE", 
         DPMASTER."AC_GRDNAME" ,DPMASTER."AC_GRDRELE", SCHEMAST."S_NAME",
        (CASE
        WHEN "AC_MBDATE" IS NULL THEN null
        ELSE  (select add_months(DPMASTER.'.$print_date.',(18*12)))
        END ) as months
        FROM DPMASTER , SCHEMAST 
        WHERE DPMASTER."AC_ACNOTYPE" = SCHEMAST."S_ACNOTYPE" 
        AND DPMASTER."AC_TYPE" = SCHEMAST.ID
        AND CAST(DPMASTER."AC_MINOR" AS integer) <> 0
        AND DPMASTER."AC_ACNOTYPE" ='.$AC_ACNOTYPE.'
        AND DPMASTER."AC_TYPE" = '.$AC_TYPE.'
        AND (CAST( DPMASTER."AC_OPDATE" AS date) IS NULL OR CAST(DPMASTER."AC_OPDATE" AS date) <= DATE('.$sdate.'))
        AND (CAST( DPMASTER."AC_CLOSEDT" AS date) IS NULL OR CAST(DPMASTER."AC_CLOSEDT" AS date) > DATE('.$edate.'))';

//   echo $query;
          
$sql =  pg_query($conn,$query);

 $i = 0;


while($row = pg_fetch_assoc($sql))
{ 
    
    $tmp=[
        
        'AC_NAME'=> $row['AC_NAME'],
        'AC_NNAME'=> $row['AC_NNAME'],
        'AC_ACNOTYPE'=> $row['AC_ACNOTYPE'],
        'AC_GRDNAME' => $row['AC_GRDNAME'],
        'AC_GRDRELE' => $row['AC_GRDRELE'],

        'AC_MBDATE' => $row['AC_MBDATE'],
        'S_NAME' => $row['S_NAME'],
        'AC_NO'=> $row['AC_NO'],
        'SCHEME'=>$row['SCHEME'],
        'AC_TYPE' => $AC_TYPE,
        'sdate' => $sdate,
        'edate' => $edate , 
        'print_date'=>  $print_date,
        'AC_ACNOTYPE' => $AC_ACNOTYPE,
    ];
    $data[$i]=$tmp;
    $i++;  
}
ob_end_clean();
// echo $query;

$config = ['driver'=>'array','data'=>$data];
// echo $filename;
 //print_r($data)
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');
    
?>