# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature:
    In order to prove that the Behat Symfony extension is correctly installed
    As a user
    I want to have a demo scenario

    Scenario: login and logout with user #1
        Given I am on "/en/login"
        When I fill in "email" with "test_user_1@gmail.com"
        When I fill in "password" with "user123"
        When I press "LOG IN"
        Then I should be on "/en/profile"
        Then I should see "Welcome, to your profile"
        When I follow "LOGOUT"
        Then I should be on "/en/"
