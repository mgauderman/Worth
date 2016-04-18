When /^I check the test checkbox$/ do
	visit 'http://localhost/worth/?tas=Debit+Card'

Then /^I upload our example csv file$/ do
	attach_file('csvInputFile', '/var/www/html/Worth/widgets/Debit Card.csv')
end

Then /^I expect to see the account (.*)$/ do |text|
	expect(page).to have_content(text)
end



