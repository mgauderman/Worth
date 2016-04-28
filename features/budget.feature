Feature: Budget Widget
    In order to track my budget
    As a user
    I need to be able to view my budget
    Scenario: Budget Widget Exists
        Given I am on the dashboard page
        Then I should see the textfield category
        And I should see the textfield new-budget
        And I should see the button budget-submit
    Scenario: Budget Widget Adds New Budgets 
        Given I am on the dashboard page
        When I enter the text Savings in category
        And I enter the text 75.00 in new-budget
        And I click on the button budget-submit
        Then I should see the label 75.00
    Scenario: Budget Widget Detects Surplus
        Given I am on the dashboard page
        Then I should see a green value for color of budget Food & Groceries 
    Scenario: Budget Widget Detects Deficit
        Given I am on the dashboard page
        Then I should see a red value for color of budget Leisure and Entertainment 
    Scenario: Budget Widget Detects Balance
        Given I am on the dashboard page
        Then I should see a yellow value for color of budget Utilities