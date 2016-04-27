
Feature: Transactions Widget
    In order to see my transactions
    As a user
    I need to be able to view my transactions for each account
    Scenario: Transactions Widget Exists
        Given I am on the dashboard page
        Then I should see the label Transactions
        And I should see a sortable table
    Scenario: Transactions Widget Displays Transactions
        Given I am on the dashboard page
        When I check the checkbox check0
        Then I should see the transactions for that account
    Scenario: Transactions Widget Has All Fields
        Given I am on the dashboard page
        Then I should see the label Date
        And I should see the label Amount
        And I should see the label Category
        And I should see the label Merchant
    Scenario: Transactions Sort By Date By Default
        Given I am on the dashboard page
        When I check the checkbox check0
        Then the transactions should be sorted by Date
    Scenario: Transactions Sort By Amount
        Given I am on the dashboard page
        When I sort by Amount in the table
        Then the transactions should be sorted by Amount
    Scenario: Transactions Sort By Category
        Given I am on the dashboard page
        When I sort by Category in the table
        Then the transactions should be sorted by Category


