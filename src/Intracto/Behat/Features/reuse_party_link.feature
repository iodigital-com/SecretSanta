@javascript @fixtures
Feature: Resend party info

  Scenario: As an organizer I want to get a party reuse link
    Given I am on a reuse party link page
    When I request the reuse info for "test1@test.com"
    Then I should see a success message
    And there should have been 1 send email
