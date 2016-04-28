require 'rspec/expectations'
require 'capybara'
#require 'capybara/mechanize'
require 'capybara/cucumber'
require 'test/unit/assertions'
#require 'mechanize'

World(Test::Unit::Assertions)

Capybara.default_driver = :selenium
Capybara.app_host = "https://localhost/worth"
Capybara.page.driver.browser.manage.window.maximize
World(Capybara)

Capybara.register_driver :webkit do |app|
  driver = Capybara::Webkit::Driver.new(app)
  driver.browser.ignore_ssl_errors
  driver
end