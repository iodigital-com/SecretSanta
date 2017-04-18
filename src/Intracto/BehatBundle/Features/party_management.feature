Feature: Manage a party

  @javascript @fixtures
  Scenario: I want to see the party management page
    Given I am on the party management page
    Then I should see the participant list

  @javascript @fixtures
  Scenario: I want add a participant to the party
    Given I am on the party management page
    When I add a participant
    Then I should have 6 participants

  @javascript @fixtures
  Scenario: I should not be able to delete the admin
    Given I am on the party management page
    When I remove the party admin
    Then I should have 5 participants
    And I should see a warning

  @javascript @fixtures
  Scenario: I want remove a participant from the party
    Given I am on the party management page
    When I remove a participant
    Then I should have 4 participants

  @javascript @fixtures
  Scenario: I should be able to delete the party
    Given I am on the party management page
    When I delete the party
    Then I should see the delete confirmation
