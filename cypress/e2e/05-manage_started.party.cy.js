import {party} from './fixtures/a_party.js'
let adminLink;

before(() => {
    cy.task('getItem', 'adminLink').then((link) => {
        adminLink = link
    })
})

beforeEach(() => {
    cy.visit(adminLink)
})

describe('Managing a party when it has started', () => {

    it('can add another participant', () => {
        cy.visit(adminLink)

        cy.get('#btn_add').click()
        cy.get('#add_participant_name').type('Extra participant 2')
        cy.get('#add_participant_email').type('extra_participant_2@iodigital.com')
        cy.get('#btn_add_confirmation').click()

        cy.contains('extra_participant_2@iodigital.com')
    })

    it('can change the party location and amount', () => {
        cy.visit(adminLink)

        cy.get('#btn_update').click()
        cy.get('#update_party_details_location').clear().type(`${party.location}`)
        cy.get('#update_party_details_amount').clear().type(`${party.amount}`)
        cy.get('#btn_update_confirmation').click()

        cy.contains('Your party details have been successfully updated')
        cy.contains(party.location);
        cy.contains(party.amount);
    })

    it('can track participant view status', () => {
        cy.visit(adminLink)

        cy.get('.view-participant-link').first().click()
        cy.contains('Your assigned buddy')
        cy.visit(adminLink)
        cy.contains('Viewed party')
    })

    it('can show an admin all wishlist contents', () => {
        cy.get('#btn_expose_wishlists').click()
        cy.contains('All Secret Santa Wishlists')
        cy.contains(party.wishlist[0].itemName)
    })

    it('can remove a participant', () => {
        cy.visit(adminLink)

        cy.get('#mysanta .participant.not-owner .link_remove_participant').first().click()
        cy.get('#btn_remove_participant_confirmation').click()
        cy.get('#mysanta .participant').should('have.length', 5)
    })

    it('can not remove the host', () => {
        cy.visit(adminLink)

        cy.get('#mysanta .participant.owner .link_remove_participant').first().click()
        cy.get('#btn_remove_participant_confirmation').click()
        cy.contains("You are hosting this party, you can't delete yourself")
    })

    it('can show the host all matches', () => {
        cy.get('#btn_expose_matches').click()

        cy.contains('This member is giving')
        cy.contains('a gift to this member')
    })
})
