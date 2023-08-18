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

describe('Managing a party before it started', () => {
    it('can be managed through admin link', () => {
        cy.visit(adminLink)
        cy.contains('Your Secret Santa party details')
    })

    it('can set the party location', () => {
        cy.contains(party.location);
    })

    it('can set the amount to spend', () => {
        cy.contains(party.amount);
    })

    it('can add an extra participant', () => {
        cy.reload()  // Without this we run into CSRF mismatch error

        cy.get('#btn_add').click()
        cy.get('#add_participant_name').type('Extra participant')
        cy.get('#add_participant_email').type('extra_participant@iodigital.com')
        cy.get('#btn_add_confirmation').click()

        cy.contains('extra_participant@iodigital.com')
    })

    it('can change the party location and amount', () => {
        cy.reload()

        cy.get('#btn_update').click()
        cy.get('#update_party_details_location').clear().type(`${party.alternative_location}`)
        cy.get('#update_party_details_amount').clear().type(`${party.alternative_amount}`)
        cy.get('#btn_update_confirmation').click()

        cy.contains('Your party details have been successfully updated')
        cy.contains(party.alternative_location);
        cy.contains(party.alternative_amount);
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
})
