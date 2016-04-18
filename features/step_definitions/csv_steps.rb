When /^I check the test checkbox$/ do
	visit 'http://localhost/worth/?tas=Debit+Card'
end

When /^I upload our example csv file$/ do
	attach_file('csv', '/var/www/html/worth/widgets/Debit Card.csv')
end

Then /^I expect to see the account (.*)$/ do |text|
	expect(page).to have_content(text)
end




		# When I click on the button csv
