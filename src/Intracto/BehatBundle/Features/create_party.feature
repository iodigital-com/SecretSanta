Feature: Create a new party

  @javascript
  Scenario: I want to create a party with 3 members
  Given I am on the homepage
  When I create a party with 3 participants
  And I choose a party date in the future
  And I choose a location
  And I choose the amount to spend
  When I create the party
  Then I should get a confirmation

  @javascript
  Scenario: I want to create a party with 5 members
  Given I am on the homepage
  When I create a party with 5 participants
  And I choose a party date in the future
  And I choose a location
  And I choose the amount to spend
  When I create the party
  Then I should get a confirmation
