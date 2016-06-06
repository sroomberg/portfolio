function Security(ticker) {
	this.ticker = ticker;
	this.sharesOwned = 0;
	this.costBasis = 0.0;
	this.totalSale = 0.0;
	this.dividends = 0.0;
	this.currentPrice = 0.0;

	var security = require('yahoo-finance-stream')({ frequency: 5000 });
	
	security.watch(this.ticker.toLowerCase());

	security.on('data', function(security) {
		this.currentPrice = security.bid;
	});
}

Security.prototype = {
	constructor: Security,
	getSharesOwned: function() {
		return this.sharesOwned;
	},
	getCostBasis: function() {
		return this.costBasis;
	},
	getTotalSale: function() {
		return this.totalSale;
	},
	addTransaction: function(type, numShares, pricePerUnit, commission) {

	},
	addDividend: function(amt) {

	},
	calculateGain: function() {

	},
	calculateRecognizedGain: function() {

	}
}