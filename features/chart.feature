Feature: Graph Widget
    In order to track my net worth 
    As a user
    I need to be able to view my net worth on a time-series graph
    Scenario: Seeing a start and end date picker when I arrive at the dashboard page
            Given I am on the dashboard page
            When I visit the site
            Then I should have 1 of input#start-datepicker selector
            And I should have 1 of input#end-datepicker selector
    Scenario: Seeing a chart when I arrive at dashboard page
            Given I am on the dashboard page
            When I visit the site
            Then I should see a chart div on the page 
    Scenario: Seeing my net worth on the graph when I arrive at dashboard page
            Given I am on the dashboard page
            When I visit the site
            Then I should see a Net Worth g on the page
    Scenario: Seeing my total assets on the graph when I arrive at dashboard page
            Given I am on the dashboard page
            When I visit the site
            Then I should see a Total Assets g on the page        
    Scenario: Seeing my total liabilities when I arrive at dashboard page
            Given I am on the dashboard page
            When I visit the site
            Then I should see a Total Liabilities g on the page
    