Then /^I should be able to see all my accounts$/ do
	expect(page).to have_text('Accounts')
end
Then /^I should see a text field to add accounts$/ do
	expect(page).to have_field('Account Name')
end


# Then /^I should see the button (.*)$/ do |butt|
# 	expect(page).to have_button(butt)
# end	