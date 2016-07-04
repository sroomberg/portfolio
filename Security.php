<?php

public class Security {
    private $ticker;
    private $shares_owned;
    private $current_price;
    private $cost_basis;
    private $total_sale;
    private $dividends;
    private $transactions;

    public function __construct($a_ticker) {
        $this->ticker = $a_ticker;
        $this->shares_owned = 0;
        $this->current_price = 0.0;
        $this->cost_basis = 0.0;
        $this->total_sale = 0.0;
        $this->dividends = array();
        $this->transactions = array();
    }

    public function order($trans_type, $date, $num_shares, $share_price, $commission) {
        $order = new Transaction();
        $order->type = $trans_type;
        $order->date = $date;
        $order->num_shares = $num_shares;
        $order->amount = $share_price * $num_shares;
        $order->commission = $commission;
        array_push($transactions, $order);
    }

    public function dividend($date, $amount) {
        $order = new Transaction();
        $order->type = 'DIV';
        $order->date = $date;
        $order->amount = $amount;
        array_push($dividends, $order);
    }

    public function calculate_gain() {

    }

    public function calculate_recogized_gain() {

    }

    private class Transaction {
        public $type;       // String BUY, SELL, DIV
        public $date;       // String date variable
        public $amount;     // floating point amount of transaction
        public $num_shares; // integer number of shares bought or sold
        public $commission; // floating point amount of commission
    }
}

?>
