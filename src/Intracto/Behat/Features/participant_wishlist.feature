@participant @javascript @fixtures
Feature: Manage a wishlist

  Scenario: As a participant I want to see my matched secret santa
    Given I am on a participant page
    Then I should see my secret santa

  Scenario: As a participant I should be able to see the wishlist of my secret santa
    Given I am on a participant page
    Then I should see the wishlist of my secret santa

  Scenario: As a participant I should be able to send a secret message to my secret santa
    Given I am on a participant page
    When I send a secret message
    Then I should see a success message
    And there should have been 1 send email

  Scenario: As a participant I want to add items to my wishlist
    Given I am on a participant page
    And I add an item "santa hat" to my wishlist
    When I reload the page
    Then I should have an item "santa hat" on my wishlist

  Scenario: As a participant I should be able to remove an item from my wishlist
    Given I am on a participant page
    And I remove an item from my wishlist
    Then I should see a success message
    When I reload the page
    Then I should have 0 items on my wishlist
