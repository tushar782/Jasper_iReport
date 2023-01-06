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

$filename = __DIR__.'/student.jrxml';

//variables
// $invoice_no = $_GET['invoice_no'];
// $cust_id = $_GET['cust_id'];
// $cust_name = $_GET['cust_name'];
// $cust_address = $_GET['cust_address'];
// $cust_phone = $_GET['cust_phone'];
// $item_id = $_GET['item_id'];
// $item_name = $_GET['item_name'];
// $item_price= $_GET['item_price'];
// $item_amt = $_GET['item_amt'];
// $item_qua = $_GET['item_qua'];
// $sub_total = $_GET['sub_total'];
// $discount = $_GET['discount'];
// $tax      = $_GET['tax'];
// $total    = $_GET['total'];


//$cust_id = str_replace(" ","" , $cust_id);
//$invoice_no = str_replace(" ","" , $invoice_no);
//$item_name = str_replace(" ","" , $item_name);



$query = 'SELECT * FROM bill
inner join custumer on custumer.custid = bill.billid
inner join itemmaster on itemmaster.itemid = bill.itemid';



$sql = pg_query($conn,$query);

//iteration

$i = 0;
$amount = 0;
$total = 0;



while ($row = pg_fetch_assoc($sql))
{
  
    $total = $row['quantity'] * $row['amount'];
    $gtotal = $gtotal + $total;
    

    $tmp=[
        'invoiceno'=>$row['invoiceno'],
        'iname'    =>$row['iname'],
        'cname'    =>$row['cname'],
        'address'  =>$row['address'],
        'custid'   =>$row['custid'],
        'itemid'   =>$row['itemid'],
        'description'=>$row['description'],
        'quantity' =>$row['quantity'],
        'amount'   =>$row['amount'],
      
        'total'   => $total,
        'gtotal'  => $gtotal,
        // 'itemid' => $itemid,
        // 'quantity'=> $quantity,
        // 'description' => $description,
        
        
        // 'iemid'   => $itemid,
        // 'iname'   => $iname,
        // 'quantity'=> $quantity,
                
    ];
    $data[$i]=$tmp;
    $i++;

}

//$faker = Faker\Factory::create('en_US');

//$conn = pg_connect("host=127.0.0.1 dbname=CBSLIVEDB user=postgres password=admin");

ob_end_clean();


//print_r($data);


$config = ['driver'=>'array','data'=>$data];
// print_r($config);
$report = new PHPJasperXML();
$report->load_xml_file($filename)    
    ->setDataSource($config)
    ->export('Pdf');

?>
