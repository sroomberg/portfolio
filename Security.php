<?php

class Security {
    public $ticker;
    public $shares_owned;
    public $shares_sold;
    public $cost_basis;
    public $total_sale;
    public $dividends;
    public $transactions;
    public $current_price;
    public $recognized_gain;

    public function __construct($a_ticker) {
        $this->ticker = $a_ticker;      // stock ticker
        $this->shares_owned = 0;        // number of shares currently owned
        $this->cost_basis = 0.0;        // total cost basis
        $this->total_sale = 0.0;        // total sale
        $this->dividends = array();     // array of Transaction objects tracking dividends
        $this->transactions = array();  // array of Transaction objects tracking BUYs and SELLs
        $this->current_price = 0.0;     // current unit price of the security
        $this->recognized_gain = 0.0;   // tax recognizable gain of position
    }

    public function order($trans_type, $date, $num_shares, $share_price, $commission) {
        $order = new Transaction();
        $order->type = $trans_type;
        $order->date = $date;
        $order->num_shares = $num_shares;
        $order->amount = $share_price * $num_shares;
        $order->commission = $commission;
        array_push($transactions, $order);

        if ($type == 'BUY') {
            $this->cost_basis += ($order->amount + $commission);
            $this->shares_owned += $num_shares;
        }
        elseif ($type == 'SELL') {
            $this->total_sale += ($order->amount + $commission);
            $this->shares_owned -= $num_shares;
            $this->shares_sold += $num_shares;

            $counter = 0;
            $recognized_amount = 0.0;
            foreach ($this->transactions as $transaction) {
                if (strcmp($transaction->type, 'BUY') == 0) {
                    if ($counter == $this->shares_sold) {
                        break;
                    }

                    $counter += $transaction->num_shares;
                    $recognized_amount += $transaction->num_shares * $transaction->amount;
                }
            }
            $this->recognized_gain = $recognized_amount;
        }
    }

    public function dividend($date, $amount) {
        $order = new Transaction();
        $order->type = 'DIV';
        $order->date = $date;
        $order->amount = $amount;
        array_push($dividends, $order);
    }

    public function get_dividend_total() {
        $ret = 0.0;
        foreach($this->dividends as $div_order) {
            $ret += $div_order->amount;
        }
        return $ret;
    }

    public function set_current_price($amount) {
        if ($amount < 0.0) {
            $amount = 0.0;
        }
        $this->current_price = $amount;
    }

    public function get_value() {
        return ($this->shares_sold * $this->$current_price);
    }

    // Used to write information to a txt file to save information for later
    // public function to_string() {
    //     $ret = '';
    //     $ret .= $this->ticker;
    //     $ret .= '\n';
    //     $ret .= $this->$shares_owned . ' ' . $this->$shares_sold . ' ' . $this->$cost_basis . ' ' . 
    //             $this->$total_sale . ' ' . $this->$current_price . ' ' . $this->$recognized_gain . '\n';
    //     $ret .= $dividends;
    //     $ret .= '\n***\n';
    //     return $ret;
    // }


}

// Class used to keep track of the transactions owned by each security
class Transaction {
    public $type;       // String BUY, SELL, DIV
    public $date;       // String date variable
    public $amount;     // floating point amount of transaction
    public $num_shares; // integer number of shares bought or sold
    public $commission; // floating point amount of commission
}

?>
