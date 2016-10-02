Feature: Create a new pool

  @javascript
  Scenario: I want to create a pool with 3 members
  Given I am on the homepage
  When I create an event with 3 participants
  And I choose an event date in the future
  And I choose a location
  And I choose the amount to spend
  When I create the event
  Then I should get a confirmation

  @javascript
  Scenario: I want to create a pool with 5 members
  Given I am on the homepage
  When I create an event with 5 participants
  And I choose an event date in the future
  And I choose a location
  And I choose the amount to spend
  When I create the event
  Then I should be able to exclude participant combinations
  When I confirm the excludes and create the party
  Then I should get a confirmation
