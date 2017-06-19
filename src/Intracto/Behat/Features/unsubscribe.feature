@javascript @fixtures
Feature: A user is able to unsubscribe from all emails

  Scenario: I want to unsubscribe from
    Given I am on the unsubscribe page
    When I want to unsubscribe from all parties
    Then I should see a success message

  Scenario: Submitting without options should show an error
    Given I am on the unsubscribe page
    When I submit the form with selecting an option
    Then I should see a error message
