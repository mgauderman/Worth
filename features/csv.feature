Feature: CSV Upload
	Scenario: CSV Upload Exists
		Given I am on the dashboard page
		And I should see the button Save
		Then I should see the button csv
	Scenario: CSV Upload Adds Account
		Given I am on the dashboard page
		When I upload our example csv file
		And I click on the button Save
		Then I should see the label Debit Card
	Scenario: CSV Upload Adds Transactions
		Given I am on the dashboard page
		When I check the test checkbox
		Then I should see 2 of Debit Card
		And I should see the label Century 16
		And I should see the label KFC
		And I should see the label Costco
		And I should see the label LAX
	Scenario: CSV Upload Checks If Asset
		Given I am on the dashboard page
		Then I should see 2 of Asset
	Scenario: CSV Upload Reads Amounts
		Given I am on the dashboard page
		Then I should see the label -24.00
		And I should see the label -12.40
		And I should see the label -152.80
		And I should see the label -726.00
	Scenario: CSV Upload Reads Category
		Given I am on the dashboard page
		Then I should see 2 of Movies
		And I should see 2 of Dining
		And I should see 2 of Groceries
		And I should see 2 of Travel 

