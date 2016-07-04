<?php

public class Portfolio {
    private $db_host = '127.0.0.1';
    private $db_uname = 'root';
    private $db_pwd = 'root';
    private $db_name = 'portfolio';
    private $cxn = new mysql_connect($db_host, $db_uname, $db_pwd);

    public $cash;
    public $securities;
    public $securities_balance;
    public $total_balance;

    public function __construct() {
        $this->connect($cxn);
        $this->cash = 0.0;
        $this->securities = array();
        $this->securities_balance = 0.0;
        $this->total_balance = $this->cash + $this->securities_balance;
    }

    public function connect($link) {
        if (!$link) {
            die('Connection Failed: ' . mysql_error());
        }

        $selected_db = mysql_select_db($db_name, $link);
        if (!$selected_db) {
            $mysql = 'CREATE DATABASE ' . $db_name;
            if (!mysql_query($mysql, $link)) {
                echo 'Error creating databse: ' . mysql_error() . '\n';
            }

            $mysql = 'CREATE TABLE Securities (
                ticker VARCHAR(30) NOT NULL PRIMARY KEY,
                current_balance FLOAT NOT NULL
            )';
            if (!mysql_query($mysql, $link)) {
                echo 'Error creating Securities table: ' . mysql_error() . '\n';
            }

            $mysql = 'CREATE TABLE Portfolio (
                cash_balance FLOAT,
                securities_balance FLOAT,
                total_balance FLOAT
            )';
            if (!mysql_query($mysql, $link)) {
                echo 'Error creating Portfolio table: ' . mysql_error() . '\n';
            }
        }
    }

    public function on_exit() {
        // commit everything to database
        mysql_close($cxn);
    }

    public function refresh() {
        //will be needed to refresh all changes to the portfolio
    }

    public function credit($amount) {
        $this->cash += $amount;
    }

    public function debit($amount) {
        $this->cash -= $amount;
    }

    public function security_transaction($ticker, $date, $type, $shares, $share_price, $commission) {
        if (!array_key_exists($ticker, $securities)) {
            $new_position = new Security($ticker);
            $new_position->order($type, $date, $shares, $share_price, $commission);
            array_push($securities, $ticker => $new_position);
        }
        else {
            $securities[$ticker]->order($type, $date, $shares, $share_price, $commission);
        }
    }

    public function pay_dividend($ticker, $date, $amount) {
        if (array_key_exists($ticker, $securities)) {
            $securities[$ticker]->dividend($date, $amount);
        }
    }

    public function calculate_gain() {
        //calculate gain for entire portfolio
    }

    public function calculate_gain($ticker) {
        if (array_key_exists($ticker, $securities)) {
            return $securities[$ticker]->calculate_gain();
        }
    }

    public function calculate_recogized_gain() {
        //calculate total recognized gain for portfolio
    }

    public function calculate_recogized_gain($ticker) {
        if (array_key_exists($ticker, $securities)) {
            return $securities[$ticker]->calculate_recogized_gain();
        }
    }

}

?>
