<?php
require 'resources/yahoo-finance-api/lib/YahooFinance/YahooFinance.php';
global $yahoo_finance = new YahooFinance;

public class Portfolio {
    private final $db_host = '127.0.0.1';
    private final $db_uname = 'root';
    private final $db_pwd = 'root';
    private final $db_name = 'portfolio';
    private final $cxn = new mysql_connect($db_host, $db_uname, $db_pwd);

    public $cash;
    public $securities;
    public $securities_balance;
    public $total_balance;

    public function __construct() {
        $conn = $this->connect($cxn);
        if ($conn == 0) {
            $this->cash = 0.0;
            $this->securities = array();
            $this->securities_balance = 0.0;
            $this->total_balance = $this->cash + $this->securities_balance;
        }
        elseif ($conn == 1) {
            // data already exists in db so you need to parse through needed data
        }
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
            array_push($this->securities, $ticker => $new_position);
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

    public function get_current_price() {
        // get current prices of all positions
    }

    public function get_current_price($ticker) {
        if (array_key_exists($ticker, ))
    }

    public function calculate_gain() {
        //calculate gain for entire portfolio
    }

    public function calculate_gain($ticker) {
        if (array_key_exists($ticker, $this->securities)) {

        }
    }

    public function calculate_recogized_gain() {
        //calculate total recognized gain for portfolio
    }

    public function calculate_recogized_gain($ticker) {
        if (array_key_exists($ticker, $this->securities)) {

        }
    }

    private function connect($link) {
        if (!$link) {
            die('Connection Failed: ' . mysql_error());
            return -1;
        }

        $selected_db = mysql_select_db($db_name, $link);
        if (!$selected_db) {
            $mysql = 'CREATE DATABASE ' . $db_name;
            if (!mysql_query($mysql, $link)) {
                echo 'Error creating databse: ' . mysql_error() . '\n';
                return -1;
            }

            $mysql = 'CREATE TABLE Securities (
                ticker VARCHAR(30) NOT NULL PRIMARY KEY,
                current_balance FLOAT NOT NULL
            )';
            if (!mysql_query($mysql, $link)) {
                echo 'Error creating Securities table: ' . mysql_error() . '\n';
                return -1;
            }

            $mysql = 'CREATE TABLE Portfolio (
                cash_balance FLOAT,
                securities_balance FLOAT,
                total_balance FLOAT
            )';
            if (!mysql_query($mysql, $link)) {
                echo 'Error creating Portfolio table: ' . mysql_error() . '\n';
                return -1;
            }
            return 0;
        }
        return 1;
    }

    private function on_exit() {
        // commit everything to database
        mysql_close($this->cxn);
    }

    private function update() {
        //will be needed to refresh all changes to the portfolio
    }

    private function string_to_date($date_str) {
        // parses string date variable to date object to be used when committing to db
        // returns date object
    }

}

?>
