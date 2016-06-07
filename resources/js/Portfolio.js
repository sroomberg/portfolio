function Portfolio(transactionDataFile) {
	this.transactionData = null;
	
	if (transactionDataFile != null || transactionDataFile != '') {
		$.getJSON(transactionDataFile, function(data) {
			this.transactionData = data;
		}).error(function() {
			alert("Something went wrong trying to parse ".concat(transactionDataFile));
		});
	}
	if (this.transactionData != null) {
		
	}
	else {
		alert("There is no transaction data. You have an empty portfolio.")
	}
}

Portfolio.prototype = {
	constructor: Portfolio,
	onExit: function() {	// Write transactionData JSON object to transactions.json

	},
}