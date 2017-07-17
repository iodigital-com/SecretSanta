@management @javascript @fixtures
Feature: Manage an unstarted party

  Scenario: I want to see the party management page
    Given I am on the party management page
    Then I should see the participant list

  Scenario: I want add a participant to the party
    Given I am on the party management page
    When I add a participant
    Then I should have 6 participants

  Scenario: I should not be able to delete the admin
    Given I am on the party management page
    When I remove the party admin
    Then I should have 5 participants
    And I should see a warning message

  Scenario: I want remove a participant from the party
    Given I am on the party management page
    When I remove a participant
    Then I should have 4 participants

  Scenario: I want to edit the details of the first participant
    Given I am on the party management page
    And I edit the first participant name to Admin
    Then I should see a success message
    When I refresh the page
    Then the name of the first participant should be Admin

  Scenario: I want to edit the email address of the first participant
    Given I am on the party management page
    And I edit the first participant email to new_test1@test.com
    Then I should see a success message
    And there should have been 0 send email
    When I refresh the page
    Then the email address of the first participant should be new_test1@test.com

  Scenario: I want to edit the email address of the first participant to an invalid email address
    Given I am on the party management page
    And I edit the first participant email to example@localhost
    Then I should see a error message
    When I refresh the page
    Then the email address of the first participant should be test1@test.com

  Scenario: I should be able to update the party details
    Given I am on the party management page
    When I update the party location to The company office
    Then the summary location info should be The company office

  Scenario: I should be able to start the party
    Given I am on the party management page
    When I start the party
    Then I should see a success message
    And there should have been 5 send emails

  Scenario: I should be able to delete the party
    Given I am on the party management page
    When I delete the party
    Then I should see the delete confirmation
