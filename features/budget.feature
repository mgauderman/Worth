Feature: Budget Widget
    In order to track my budget
    As a user
    I need to be able to view my budget
    Scenario: Budget Widget Exists
        Given I am on the dashboard page
        Then I should see the dropdown menu Category
        And I should see the button Submit
    Scenario: Budget Widget Adds Budgets 
        Given I am on the dashboard page
        When I select Movies from the dropdown menu Category
        And I set the slider amount for Movies to 50.00
        And I click on the button Submit
        Then I should see the label 50.00
        And I should see the label Movies
    Scenario: Budget Widget Detects Surplus
        Given I am on the dashboard page
        When I create a new budget for Movies
        Then the line Movies should be colored Green
    Scenario: Budget Widget Detects Deficit
        Given I am on the dashboard page
        When I import a transaction for the budget Movies valued at -75.00
        Then the line Movies should be colored Red
    Scenario: Budget Widget Detects Balance
        Given I am on the dashboard page
        When I import a transaction for the budget Movies valued at 25.00
        Then the line Movies should be colored Yellow
    Scenario: Budget Widget Contains Categories
        Given I am on the dashboard page
        When I click on the dropdown Category
        Then the dropdown menu Category should contain Movies
        And the dropdown menu Category should contain Food
        And the dropdown menu Category should contain Clothes
        And the dropdown menu Category should contain School Supplies
        And the dropdown menu Category should contain Travelling 
        And the dropdown menu Category should contain Books
        And the dropdown meny Category should contain Music
        And the dropdown menu Category should contain Other

