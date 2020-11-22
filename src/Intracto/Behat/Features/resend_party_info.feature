@javascript @fixtures
Feature: Resend party info

  Scenario: As a participant or organizer I want to see all my active parties
    Given I am on a resend party info page
    When I request the party info for "test1@example.com"
    Then I should see a success message
    And there should have been 1 send email

  Scenario: I request to resend the party info with an incorrect email address
    Given I am on a resend party info page
    When I request the party info for "unknown.email@example.com"
    Then I should see an error message
    And there should have been 0 send email
