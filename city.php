<?php
error_reporting(0);
ob_start(); 
include "main.php";
require_once('connection.php');

// set_time_limit(100);
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
use simitsdk\phpjasperxml\PHPJasperXML;

$filename = __DIR__.'/city.jrxml';

$data = [];
$faker = Faker\Factory::create('en_US');

 //$conn = pg_connect("host=127.0.0.1 dbname= user=postgres password=tushar");


 $query = 'SELECT * from city';


$sql = pg_query($conn,$query);

$i=0;
while ($row = pg_fetch_assoc($sql))
{
    $tmp=[


       //'Name'=>'John'
     'name'  => $row['name'],
    // 'city' => $city,
    //    'custid'   =>$row['custid'],
    //    'itemid'   =>$row['itemid'],
    //    'description'=>$row['description'],
    //    'quantity' =>$row['quantity'],
   
     
    ];
    $data[$i]=$tmp;
    $i++;
 
}
ob_end_clean();

// print_r($data);

$config = ['driver'=>'array','data'=>$data];
// print_r($config);
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');

?>
