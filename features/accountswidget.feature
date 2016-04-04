Feature: Accounts Widget
	In order to see my accounts
	As a user
	I need to be able view my accounts
	Scenario: Seeing a list of all my accounts
		Given I am on the dashboard page
		Then I should be able to see all my accounts
	Scenario: Add/Remove Buttons
		Given I am on the dashboard page
		When I visit the site
		Then I should see the textfield Account Name
		And I should see the button Add
		And I should see the button Remove
	Scenario: Add Accounts
		Given I am on the dashboard page
		When I enter the text Visa Card
		And I click on the button Add
		Then I should see Visa Card