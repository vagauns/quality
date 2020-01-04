<?php 
	
	include_once './dbclass.php';
	try 
	{
		$dbclass = new DBClass(); 
		$connection = $dbclass->getConnection();
		$sql = file_get_contents("./data/database.sql"); 

		$connection->exec($sql);

		//populate database with various products
		$start_date = '2019-09-30 00:00:00';

		for ($i=0; $i < 94; $i++): 
			//change day after populate
			$start_date = date('Y-m-d H:i:s', strtotime($start_date . ' +1 day'));
			
			for ($o=1; $o <= 7; $o++):
				$qtt = rand(0,50);
				$a = $connection->exec(
					"INSERT INTO `vendas` (produto_id, quantity, created_at, updated_at) VALUES ({$o}, {$qtt}, '{$start_date}', '{$start_date}');"
				);

			endfor;

		endfor;


		echo "Database and tables created successfully!";
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}