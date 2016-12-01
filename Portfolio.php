<?php
require 'Security.php';

class Portfolio {

    public $cash;
    public $securities;
    public $securities_balance;
    public $total_balance;

    public function __construct($cash_amount = NULL) {
        if (is_null($cash_amount)) {
            echo(
                "<script type='text/javascript'>
                    var cash_balance = prompt('Please enter a starting balance (default value is $10,000):');
                </script>
            ");
            $cash_balance = "<script type='text/javascript'> document.write(cash_balance); </script>";
            $cash_balance = (float) $cash_balance;
            if ($cash_balance == 0.0 || is_null($cash_balance)) {
                $cash_balance = 10000.0;
            }
        }
        $this->credit($cash_balance);
    }

    public function credit($amount) {
        $this->cash += $amount;
    }

    public function debit($amount) {
        $this->cash -= $amount;
    }

    public function security_transaction($ticker, $date, $type, $shares, $share_price, $commission) {
        if (!array_key_exists($ticker, $this->securities)) {
            $new_position = new Security($ticker);
            $new_position->order($type, $date, $shares, $share_price, $commission);
            array_push($this->securities[$ticker], $new_position);
        }
        else {
            $this->securities[$ticker]->order($type, $date, $shares, $share_price, $commission);
        }
    }

    public function pay_dividend($ticker, $date, $amount) {
        if (array_key_exists($ticker, $this->securities)) {
            $this->securities[$ticker]->dividend($date, $amount);
        }
    }

    // functionality for live quote updates -- not using this in this version
    // public function get_current_price($ticker) {
    //     if (array_key_exists($ticker, $this->securities)) {
    //         $quote = json_decode($yahoo_finance->getQuotes([$ticker]));
    //         return ($this->securities[$ticker]->current_price = number_format((float) $quote->query->results->quote->LastTradePriceOnly, 2, '.', ''));
    //     }
    //     return 0.0;
    // }

    // public function update_current_prices() {
    //     $quote = json_decode($yahoo_finance->getQuotes(array_keys($this->securities)));
    //     foreach($quote->query->results->quote as $stock) {
    //         $this->securities[$ticker]->current_price = number_format((float) $stock->LastTradePriceOnly, 2, '.', '');
    //     }
    // }

    public function calculate_gain($ticker = NULL, $include_dividends) {
        if ($ticker != NULL) {
            if (array_key_exists($ticker, $this->securities)) {
                $current_price = $this->get_current_price($ticker);

                $position = $this->securities[$ticker];
                $num_shares = $position->shares_owned;
                $cost_basis = $position->cost_basis;
                $total_sale = $position->total_sale;

                $ret = (($num_shares * $current_price) - $cost_basis + $total_sale);

                if ($include_dividends) {
                    foreach($position->dividends as $div) {
                        $ret += $div;
                    }
                }

                return number_format((float) $ret, 2, '.', '');
            }
            return 0.0;
        }

        $ret = 0.0;
        foreach ($this->securities as $position) {
            $ret += $this->calculate_gain($position->ticker, $include_dividends);
        }
        return number_format((float) $ret, 2, '.', '');
    }

    public function calculate_recogized_gain($ticker = NULL) {
        if ($ticker != NULL) {
            if (array_key_exists($ticker, $this->securities)) {
                return $this->securities[$ticker]->recognized_gain;
            }
            return 0.0;
        }

        $ret = 0.0;
        foreach ($this->securities as $position) {
            $ret += $this->calculate_recogized_gain($position->ticker);
        }
        return $ret;
    }

}

?>
