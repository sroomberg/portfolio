function Portfolio(transactionDataFile) {
	this.transactionData = null;
	this.cashBalance = 0.0;
	this.securityBalance = 0.0;
	this.accountBalance = this.cashBalance + this.securityBalance;
	this.securityList = [];

	if (transactionDataFile != null || transactionDataFile != '') {
		$.getJSON(transactionDataFile, function(data) {
			this.transactionData = data;
		}).error(function() {
			alert("Something went wrong trying to parse ".concat(transactionDataFile));
		});

		if (this.transactionData != null) {
			var i, j;
			for (i = 0; i < this.transactionData.length; i++) {
				if (this.transactionData[i] == 'ACCTCASH') {
					for (j = 0; j < this.transactionData.ACCTCASH.length; j++) {
						var current = this.transactionData.ACCTCASH[j];
						if (current.transType.toUpperCase() == 'CR' || current.transType.toUpperCase() == 'DR') {
							addCashTransaction(current.date, current.transType.toUpperCase(), current.amount);
						}
						else {
							console.log(this.accountData.ACCTCASH[j].transType.concat(" is not defined AT INDEX " + j + ". Please read documentation and fix this error."));
						}
					}
				}
				else {
					var current = this.transactionData[i];
					for (j = 0; j < this.transactionData[i].length; j++) {
						if (current[j].transType.toUpperCase() == 'BUY' || current[j].transType.toUpperCase() == 'SELL') {
							addSecurityTransaction(current[j].date, current[i], current[j].transType.toUpperCase(), current[j].numShares, current[j].unitPrice, current[j].commission);
						}
						else if (this.transactionData[i][j].transType.toUpperCase() == 'DIVIDEND') {

						}
						else {
							console.log(this.accountData.ACCTCASH[j].transType.concat(" is not defined AT INDEX " + j + ". Please read documentation and fix this error."));
						}
					}
				}
			}
		}
	}
}

Portfolio.prototype = {
	constructor: Portfolio,
	getAccountBalance: function() {
		return (this.cashBalance + this.securityBalance);
	},
	getCashBalance: function() {
		return this.cashBalance;
	},
	getSecurityBalance: function() {
		return this.securityBalance;
	},
	addCashTransaction: function(date, type, amt) {

	},
	addSecurityTransaction: function(date, ticker, type, shares, pricePerUnit, commission, amt) {

	},
	displayHoldings() {
		var ret = '';
		return ret;
	},
	onExit: function() {

	}
}