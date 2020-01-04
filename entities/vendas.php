<?php


class Venda {

    // Connection instance
    private $connection;

    // table name
    private $table_name = "vendas";

    // table columns
    public $id;
    public $produto_id;
    public $quantity;
    public $created_at; 
    public $updated_at;

    public function __construct($connection){
        $this->connection = $connection;
    }

    //C
    public function create(){
    }
    //R
    public function read(){
    }
    //U
    public function update(){}
    //D
    public function delete(){}
}