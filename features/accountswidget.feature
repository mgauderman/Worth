Feature: Accounts Widget
	In order to see my accounts
	As a user
	I need to be able view my accounts
	Scenario: Seeing a list of all my accounts
		Given I am on the dashboard page
		Then I should be able to see all my accounts
	Scenario: Checkbox Box Persists Through Refresh
		Given I am on the dashboard page
		When I visit the site
		And I check the checkbox Visa
		And I refresh the page
		Then I should see Visa checked
	Scenario: Add/Remove Buttons
		Given I am on the dashboard page
		When I visit the site
		Then I should see the textfield Account Name
		And I should see the button Add
		And I should see the button Delete
	Scenario: Add Accounts
		Given I am on the dashboard page
		When I enter the text Visa Card in Account Name
		And I click on the button Add
		Then I should see the label Visa Card
	Scenario: Checking Box Updates Transactions
		Given I am on the dashboard page
		When I check the checkbox Visa Credit Card
		Then I should see 2 of Visa Credit Card

	Scenario: Remove Accounts
		Given I am on the dashboard page
		And I enter the text Visa Card in Account Name
		And I click on the button Delete
		Then I must not see the label Visa Card
