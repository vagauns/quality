<?php
header("Content-Type: application/json; charset=UTF-8");

include_once './config/dbclass.php';
include_once './entities/vendas.php';

$dbclass = new DBClass();
$connection = $dbclass->getConnection();

$vendas = new Vendas($connection);

$from 		= $_POST['start'];
$to 		= $_POST['end'];
$group 		= $_POST['group'];

//get total of days to filter groups
$total = $vendas->getTotalDate($from, $to);
$itens = 0;

if( $group == 'mensal' ){
	if( $total < 58 ){
		echo json_encode( array("error" => true, "count" => 0, "message" => "Numero de dias Inferior a 2 meses!") );
		die();
	} 

	$itens = $vendas->readMonths($from, $to);
	$count = $itens->rowCount();

	if($count > 0){

			
		// $products = array();
		$groups = array();
		$i = 0;
		while ($row = $itens->fetch(PDO::FETCH_ASSOC)){


			extract($row);

			$groups[$produto_id]['id'] = $produto_id;
			$groups[$produto_id]['name'] = $name;
			$groups[$produto_id]['obj'][$i]['dia'] = "Month {$months}";
			$groups[$produto_id]['obj'][$i]['quantidade'] = $quantity;

			$i++;
		}

		//var_dump($groups); die();

		echo json_encode( array("body" => $vendas->returnTableHtml($groups), "count" => $count) );
		die();
	}


} else if( $group == 'semanal' ){
	if( $total < 13 ){
		echo json_encode( array("error" => true, "count" => 0, "message" => "Numero de dias Inferior a 2 semanas!") );
		die();
	}

	$itens = $vendas->readWeeks($from, $to);
	$count = $itens->rowCount();

	if($count > 0){

			
		// $products = array();
		$groups = array();
		$i = 0;
		while ($row = $itens->fetch(PDO::FETCH_ASSOC)){


			extract($row);

			$groups[$produto_id]['id'] = $produto_id;
			$groups[$produto_id]['name'] = $name;
			$groups[$produto_id]['obj'][$i]['dia'] = "week {$weeks}";
			$groups[$produto_id]['obj'][$i]['quantidade'] = $quantity;

			$i++;
		}

		//var_dump($groups); die();

		echo json_encode( array("body" => $vendas->returnTableHtml($groups), "count" => $count) );
		die();
	}

} else if ( $group == 'diario' ){
	if( $total < 2 ){
		echo json_encode( array("error" => true, "count" => 0, "message" => "Numero de dias Inferior a 2 dias") );
		die();
	}

	$itens = $vendas->readDays($from, $to);
	$count = $itens->rowCount();

	if($count > 0){

		// $products = array();
		$groups = array();
		$i = 0;
		while ($row = $itens->fetch(PDO::FETCH_ASSOC)){

			extract($row);

			$groups[$produto_id]['id'] = $produto_id;
			$groups[$produto_id]['name'] = $name;
			$groups[$produto_id]['obj'][$i]['dia'] = "$dia/$mes/$year";
			$groups[$produto_id]['obj'][$i]['quantidade'] = $quantity;

			$i++;
		}

		echo json_encode( array("body" => $vendas->returnTableHtml($groups), "count" => $count) );
		die();
	}
}


echo json_encode( array("body" => array(), "count" => 0) );
die();


// $count = $itens->rowCount();

// if($count > 0){


// 	// $products = array();
// 	$groups = array();
// 	$i = 0;
// 	while ($row = $itens->fetch(PDO::FETCH_ASSOC)){

// 		extract($row);

// 		$groups[$produto_id]['id'] = $produto_id;
// 		$groups[$produto_id]['name'] = $name;
// 		$groups[$produto_id]['obj'][$i]['dia'] = "$dia/$mes/$year";
// 		$groups[$produto_id]['obj'][$i]['quantidade'] = $quantity;

// 		$i++;
// 	}

// 	echo json_encode( array("body" => $vendas->returnTableHtml($groups), "count" => $count) );

// } else {

// 	echo json_encode( array("body" => array(), "count" => 0) );
// }