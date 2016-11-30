<?php
require 'Portfolio.php';
global $my_portfolio = new Portfolio();
?>

<html>
<style type="text/css">
	#securities_table { text-align: center; };
</style>
<head>
	<!--Bootstrap CSS--><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="resources/style.css">
	<title>Portfolio Tracker</title>
</head>
<body>
	<div class="container">
		<div class="col-md-2">
			<h3>Cash Balance: <?php echo($my_portfolio->$cash); ?></h3>
			<form method="post" id="cash_form">
				<select name="debit_or_credit" form="cash_form">
					<option value="credit">Credit</option>
					<option value="debit">Debit</option>
				</select>
				Amount: <input type="number" name="dc_amount"><br />
				<input type="submit" name="cash_submit">
			</form>
			<?php
			if (isset($_POST['cash_submit'])) {
				if (isset($_POST['dc_amount']) && $_POST['dc_amount'] != 0.0) {
					if ($_POST['debit_or_credit'] == 'credit') {
						$my_portfolio->credit($_POST['dc_amount']);
					}
					else {
						$my_portfolio->debit($_POST['dc_amount']);
					}
				}
			}
			?>
		</div>
		<div class="col-md-8">
			<div id="securites_table">
				<div class="col-md-2">Ticker</div>
				<div class="col-md-2">Dividends</div>
				<div class="col-md-2">Units Owned</div>
				<div class="col-md-2">Unit Price</div>
				<div class="col-md-2">Total Value</div>
				<div class="col-md-2">Total Gain</div>
				<hr />
				<?php
				foreach ($my_portfolio->securites as $position) {
					?><div class"col-md-2"><?php echo($position->$ticker); ?></div><?php
					?><div class"col-md-2"><?php echo($position->get_dividend_total()); ?></div><?php
					?><div class"col-md-2"><?php echo($position->$current_price); ?></div><?php
					?><div class"col-md-2"><?php echo($position->get_value()); ?></div><?php
					?><div class"col-md-2"><?php echo($my_portfolio->calulate_gain($position->$ticker, TRUE)); ?></div><?php
				}
				?>
				<hr />
				<form method="post" id="securites_form">
					<div id="securites_form_opt">Type:<br />
						<select name="trans_type" form="securites_form">
							<option value="buy">Buy</option>
							<option value="sell">Sell</option>
							<option value="dividend">Dividend</option>
						</select>
					</div>
					<div id="securites_form_opt">Date (MMDDYYYY):<br />
						<input type="text" name="date">
					</div>
					<div id="securites_form_opt">Ticker:<br />
						<input type="text" name="ticker">
					</div>
					<div id="securites_form_opt">Shares:<br />
						<input type="number" name="num_shares">
					</div>
					<div id="securites_form_opt">Unit Price:<br />
						<input type="number" name="price_per_share">
					</div>
					<div id="securites_form_opt">Dividend Amount<br />
						<input type="number" name="div_amount">
					</div>
					<div id="securites_form_opt">Commission:<br />
						<input type="number" name="commssion">
					</div>
					<input type="submit" name="securites_submit">
				</form>
				<?php if (isset($_POST['securites_submit'])) {
					$error = FALSE;
					if (isset($_POST['date'])) {
						// parse date
						$date_str = $_POST['date'];
						$date_month = (int) substr($date_str, 0, 2);
						$date_day = (int) substr($date_str, 2, 2);
						$date_year = (int) substr($date_str, 4);
						if ($date_month < 1 || $date_month > 12) {
							echo('<p class="error_msg">Invalid month</p>');
							$error = TRUE;
						}
						elseif ($date_month == 2 && $date_day > 28) {
							if ($date_year % 4 == 0) {
								if ($date_day > 29) {
									echo('<p class="error_msg">Invalid day</p>');
									$error = TRUE;
								}
							}
							else {
								echo('<p class="error_msg">Invalid day</p>');
								$error = TRUE;
							}
						}

						if ($date_day < 1 || $date_day > 31) {
							echo('<p class="error_msg">Invalid day</p>');
							$error = TRUE;
						}

						if ($date_month == 2 || $date_month == 4 || $date_month == 6
							|| $date_month == 9 || $date_month == 11) {
							if ($date_day > 30) {
								echo('<p class="error_msg">Invalid day</p>');
								$error = TRUE;
							}
						}

						$date_output = $date_month.'/'.$date_day.'/'.$date_year;
					}

					if (isset($_POST['ticker'])) {
						$ticker = $_POST['ticker'];
					}
					else {
						echo('<p class="error_msg">Invalid ticker</p>');
						$error = TRUE;
					}

					if (!$error) {
						if ($_POST['trans_type'] == 'dividend') {
							$div_output = NULL;
							if ($_POST['div_amount'] > 0.0) {
								$div_output = $_POST['div_amount'];
							}
							else {
								echo('<p class="error_msg">Invalid dividend amount</p>');
								$error = TRUE;
							}

							if (!$error) {
								$my_portfolio->pay_dividend($ticker, $date_output, $div_output);
							}
						}
						else {
							if ($_POST['trans_type'] == 'buy') {
								$trans_type = 'BUY';
							}
							else {
								$trans_type = 'SELL';
							}

							if (isset($_POST['num_shares'])) {
								$num_shares = (int) $_POST['num_shares'];
								if ($num_shares < 1) {
									echo('<p class="error_msg">Invalid number of shares</p>');
									$error = TRUE;
								}
							}
							else {
								echo('<p class="error_msg">Invalid number of shares</p>');
								$error = TRUE;
							}

							if (isset($_POST['price_per_share'])) {
								$share_price = $_POST['price_per_share'];
								if ($share_price < 0.01) {
									echo('<p class="error_msg">Invalid unit price</p>');
									$error = TRUE;
								}
							}
							else {
								echo('<p class="error_msg">Invalid unit price</p>');
								$error = TRUE;
							}

							if (isset($_POST['commssion'])) {
								$commission = $_POST['commission'];
								if ($commssion < 0) {
									$commssion = 0.0;
								}
							}
							else {
								$commssion = 0.0;
							}

							if (!$error) {
								$my_portfolio->security_transaction($ticker, $date_output, $trans_type, $num_shares, $share_price, $commission);
							}
						}
					}
				} ?>
			</div>
		</div>
		<div class="col-md-2"></div>
	</div>
	<!--jQuery--><script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
	<!--Bootstrap JS--><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>
