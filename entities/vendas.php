<?php


class Vendas {

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


    public function readDays($from, $to){

        $query = "SELECT vd.*, pr.name, DAY(vd.created_at) AS dia, MONTH(vd.created_At) as mes, YEAR(vd.created_at) as year
                FROM vendas AS vd 
                INNER JOIN produtos AS pr ON vd.produto_id = pr.id
                WHERE vd.created_at BETWEEN '".date('Y-m-d', strtotime($from))." 00:00:00' AND '".date('Y-m-d', strtotime($to))." 00:00:00' ORDER BY created_at ASC" ;

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;

    }

    public function readWeeks($from, $to){

        $query = "SELECT CONCAT(YEAR(vd.created_at), '/', WEEK(vd.created_at)) AS week_name, 
                           YEAR(vd.created_at) AS years, WEEK(vd.created_at) AS weeks, sum(vd.quantity) AS quantity, vd.produto_id, pr.name
                    FROM vendas AS vd
                    INNER JOIN produtos AS pr ON vd.produto_id = pr.id
                    WHERE vd.created_at BETWEEN '".date('Y-m-d', strtotime($from))." 00:00:00' AND '".date('Y-m-d', strtotime($to))." 00:00:00'
                    GROUP BY YEAR(vd.created_at), WEEK(vd.created_at), vd.produto_id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;

    }

    public function readMonths($from, $to){

        $query = "SELECT CONCAT(YEAR(vd.created_at), '/', MONTH(vd.created_at)) AS month_name, 
                       YEAR(vd.created_at) AS years, MONTh(vd.created_at) AS months, sum(vd.quantity) AS quantity, vd.produto_id, pr.name
                FROM vendas AS vd
                INNER JOIN produtos AS pr ON vd.produto_id = pr.id
                WHERE vd.created_at BETWEEN '".date('Y-m-d', strtotime($from))." 00:00:00' AND '".date('Y-m-d', strtotime($to))." 00:00:00'
                GROUP BY YEAR(vd.created_at), MONTH(vd.created_at), produto_id";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalDate($start, $end){

        $startTimeStamp = strtotime($start);
        $endTimeStamp = strtotime($end);

        $timeDiff = abs($endTimeStamp - $startTimeStamp);

        $numberDays = $timeDiff/86400;  // 86400 seconds in one day

        // and you might want to convert to integer
        $numberDays = intval($numberDays);

        return $numberDays;
    }


    public function returnTableHtml($itens){



        $html = '
        <table class="tg table">
          <tr>
            <th class="tg-v4zq" rowspan="2">Produto</th>';
    

                foreach($itens[1]['obj'] as $key => $it):
                    $html .= '<th class="tg-8ytw" colspan="2">'.$it['dia'].'</th>';
                endforeach;


        $html .= '
            <th class="tg-q86v" rowspan="2">media</th>
          </tr>
          <tr>';
                foreach($itens[1]['obj'] as $key => $it):
                    $html .= '<td class="tg-ktv8">qtd</td>
                              <td class="tg-8ytw">Perc</td>';
                endforeach;

        $html .= '</tr>';

        $collumns = array();
        $atz = 0;
        foreach($itens as $key => $item):

            $nitem = array();

          $html .= '
                  <tr>
                    <td class="tg-awv6">'.$item['name'].'</td>';

                    $numb = 0;
                    $itz = 0;
                    foreach($item['obj'] as $kay => $it):

                        $perc = $this->getPercentageChange($numb, $it['quantidade']);
                        $html .= '<td class="tg-0lax">'.$it['quantidade'].'</td><td class="tg-0lax">'.$perc['formatted'].'</td>';
                        $numb = $it['quantidade'];

                        $collumns[$itz]['qtd'][] = $it['quantidade'];
                        $collumns[$itz]['per'][] = $perc['resfor'];
                        $collumns[$key]['mdn'][] = $it['quantidade'];

                        $itz++;

                    endforeach;
                    
                    $mumber = ( array_sum($collumns[$key]['mdn']) / count($collumns[$key]['mdn']) );
                    $html .= '<td class="tg-wse6">'. number_format($mumber, 2, '.', '') .'</td>
                  </tr>';
            $atz++;
        endforeach;

        $html .= '
          <tr>
            <td class="tg-awv6">Total</td>'; 
            $ita = 0;
            foreach($itens[1]['obj'] as $key => $item):

                $html .= '<td class="tg-awv6">'. array_sum($collumns[$ita]['qtd']) .'</td>
                <td class="tg-awv6">'. array_sum($collumns[$ita]['per']) .'% </td>';

                $ita++;
            endforeach;
             

          $html .= '<td class="tg-y6fn"></td></tr>
        </table>';


        //var_dump($collumns);


        return $html;
    }


    function getPercentageChange( $oldNumber , $newNumber , $format = true , $invert = false ){

        $value      = $newNumber - $oldNumber;

        $change     = '';
        $sign       = '';

        $result     = 0.00;

        if ( $invert ) {
             if ( $value > 0 ) {
            //  going UP
                $change             = 'up';
                $sign               = '+';
                if ( $oldNumber > 0 ) {
                    $result         = ($newNumber / $oldNumber) * 100;
                } else {
                    $result     = 100.00;
                }

            }elseif ( $value < 0 ) {        
            //  going DOWN
                $change             = 'down';
                //$value                = abs($value);
                $result             = ($oldNumber / $newNumber) * 100;
                $result             = abs($result);
                $sign               = '-';

            }else {
            //  no changes
            }

        }else{

            if ( $newNumber > $oldNumber ) {

                //  increase
                $change             = 'up';

                if ( $oldNumber > 0 ) {

                    $result = ( ( $newNumber / $oldNumber ) - 1 )* 100;

                }else{
                    $result = 100.00;
                }

                $sign               = '+';

            }elseif ( $oldNumber > $newNumber ) {

                //  decrease
                $change             = 'down';

                if ( $oldNumber > 0 ) {

                    $result = ( ( $newNumber / $oldNumber ) - 1 )* 100;

                } else {
                    $result = 100.00;
                }

                $sign               = '-';

            }else{

                //  no change

            }

            $result = abs($result);

        }

        $result_formatted       = number_format($result, 2, '.', '');

        if ( $invert ) {
            if ( $change == 'up' ) {
                $change = 'down';
            }elseif ( $change == 'down' ) {
                $change = 'up';
            }else{
                //
            }

            if ( $sign == '+' ) {
                $sign = '-';
            }elseif ( $sign == '-' ) {
                $sign = '+';
            }else{
                //
            }
        }
        if ( $format ) {
            $formatted          = '<span class="going '.$change.'">'.$sign.''.$result_formatted.' %</span>';
        } else{
            $formatted          = $result_formatted;
        }

        return array( 'change' => $change , 'result' => $result , 'formatted' => $formatted, 'resfor' => "$sign" . $result_formatted );
    }

}