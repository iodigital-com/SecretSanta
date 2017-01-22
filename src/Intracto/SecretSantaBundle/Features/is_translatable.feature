Feature: Is the homepage translatable

  @javascript
  Scenario: I want to switch to Español
    Given I am on the homepage
    When I click on language selector and I choose Español
    Then I should see the site in Español