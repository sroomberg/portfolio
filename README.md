## Equity Portfolio Tracker

This is an <em>unfinished</em> and <em>currently non-functional</em> stock portfolio tracker. Developed entirely in PHP, you can easily, and automatically calculate gain in your portfolio.

To save information, I implemented PHP Sessions. Each time a transaction is recorded, the page refreshes and Sessions allows you not to lose your data.

To use, you must run this project on a server (localhost or remote).

## Current Problems

*Users cannot enter a starting balance and therefore has a starting balance of $10,000.00.

*Security transactions are not being recorded.

*If you run the code on your server, you'll notice the page is not styled at all.

## Future Plans

*Live stream quotes from the Yahoo Finance API so the user does not have to update the current price manually.

*Implement a database so users can access their portfolio from any computer.

*Implement a portfolio diversity graphic along with other statistics in the right-most column (currently empty).