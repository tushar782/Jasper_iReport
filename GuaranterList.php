<?php

ob_start(); 
include "main.php";
require_once('dbconnect.php');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/GuaranterList1.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

 $conn = pg_connect("host=127.0.0.1 dbname=bank user=postgres password=tushar");

$bankName = $_GET['bankName'];
$startDate = $_GET['startDate'];
$enddate = $_GET['enddate'];
$dateformate = "'DD/MM/YYYY'";
$scheme = $_GET['scheme'];
$branch = $_GET['branch'];
$startDate = "'01/01/2020'";
$enddate = "'07/08/2021'";


 $query = ' SELECT idmaster."AC_NAME",customeraddress."AC_ADDR" as addres,lnmaster."AC_CLOSEDT",lnmaster."AC_NO"
            From customeraddress,idmaster,lnmaster
            Union All
            Select customeraddress."AC_ADDR" as addres, guaranterdetails."AC_NAME" as gname,lnmaster."AC_CLOSEDT",lnmaster."AC_NO"
            From customeraddress,guaranterdetails,lnmaster
            where 
            cast("AC_CLOSEDT" as date) 
            between to_date('.$startDate.','.$dateformate.') and to_date('.$enddate.','.$dateformate.')
            ';
// $query ='  SELECT "AC_NAME",addres,"AC_CLOSEDT","AC_NO" 
//             FROM(
//              SELECT
//                    idmaster."AC_NAME",customeraddress."AC_ADDR" as addres,lnmaster."AC_CLOSEDT",lnmaster."AC_NO"
//             From 
//                    idmaster
//                   inner join lnmaster on idmaster."id"=lnmaster."AC_CUSTID"
//                   inner join customeraddress on idmaster."id"=customeraddress."idmasterID"
//             Union All
//             Select 
//                     customeraddress."AC_ADDR" as addres, guaranterdetails."AC_NAME" as gname,lnmaster."AC_CLOSEDT",lnmaster."AC_NO"
//             From 
//                     lnmaster
//                    inner join customeraddress on lnmaster."AC_CUSTID"=customeraddress."idmasterID"
//                    inner join guaranterdetails on lnmaster."AC_CUSTID"=guaranterdetails."lnmasterID"
            
//             )Result
            // -- where 
            // --       cast("AC_CLOSEDT" as date) 
            // --       between to_date('.$startDate.','.$dateformate.') and to_date('.$enddate.','.$dateformate.')
            //       ';


          
$sql =  pg_query($conn,$query);

$i = 0;
while($row = pg_fetch_assoc($sql)){ 

     $tmp=[
        'AC_NO' => $row['AC_NO'],
        'AC_NAME'=> $row['AC_NAME'],
        'addres'=> $row['addres'],
        'AC_CLOSEDT' => $row['AC_CLOSEDT'],
        'gname'=> $row['gname'],
        'gaddres'=> $row['gaddres'],
        'NAME'=> $row['NAME'],
        'AC_ACNOTYPE' => $scheme,
        'NAME' => $branch ,
        'START_DATE' => $startDate,
        'END_DATE' => $enddate,
        'bankName' => $bankName,
     ];
    
    $data[$i]=$tmp;
    $i++;
    
}
ob_end_clean();

$config = ['driver'=>'array','data'=>$data];

$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');

?> 
    

