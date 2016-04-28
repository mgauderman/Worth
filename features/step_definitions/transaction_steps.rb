Then /^the transactions should be sorted by (.*)$/ do |field|
	expect(page).to have_css("th", text: field)
	# find()
end

Then /^I should see a sortable table$/ do
	expect(page).to have_css('table');
end

When /^I sort by (.*) in the table$/ do |field|
		within('table#transactionsTable') do
			find('th', text:field).click
		end
end



# When /^I select (.*) from the dropdown menu Sort$/
# end

