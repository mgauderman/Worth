
Then /^I should see a yellow value for color of budget (.*)$/ do |text|
	expect(((page.all('td', :text => text))[1]).native.css_value('color')).to eq('rgba(255, 255, 0, 1)')
end

Then /^I should see a green value for color of budget (.*)$/ do |text|
	expect(((page.all('tr', :text => text))[1]).native.css_value('color')).to eq('rgba(0, 128, 0, 1)')
end

Then /^I should see a red value for color of budget (.*)$/ do |text|
	expect(((page.all('td', :text => text))[1]).native.css_value('color')).to eq('rgba(255, 0, 0, 1)')
end