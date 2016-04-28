
Then /^I should see a (.*) value for color of budget (.*)$/ do |value, text|
	expect((page.first('tr', :text => text)).native.css_value('color')).to eq(value)
end