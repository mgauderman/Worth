Then /^I should be able to see all my accounts$/ do
	expect(page).to have_text('Accounts')
end
Then /^I should see a text field to add accounts$/ do
	expect(page).to have_field('Account Name')
end

When /^I visit the site$/ do
end

When /^I enter the text Visa Card$/ do
	# if(page.has_button?('addAccount'))
	fill_in('accountName', with: "Visa Card")
	click_button('Add')
	# end
end
# Then /^I should see the button (.*)$/ do |butt|
# 	expect(page).to have_button(butt)
# end