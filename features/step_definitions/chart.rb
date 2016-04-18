Then /^I should see my (.*) displayed over the last 3 months on the graph$/ do |field|
	expect(page).to have_field(field)
end

When /^I enter the dates (.*) and (.*) in (.*) and (.*)$/ do |text1, text2, field1, field2|
	fill_in(field1, with: text1)
	fill_in(field2, with: text2)
end

Then /^I should see an error on the screen$/ do
	expect(page).to have_content("error")
end

Then /^I should see a (.*) g on the page$/ do |field|
    expect(page).to have_content(field)
end


Then /^I should see a (.*) div on the page$/ do |field|
	expect(page).to have_css('div#' + field)
end

Then /^I should see a (.*) input on the page$/ do |field|
	expect(page).to have_css('input#' + field)
end
