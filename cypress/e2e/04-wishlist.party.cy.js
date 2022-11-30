import {party} from './fixtures/a_party.js'
let adminLink;

before(() => {
    cy.task('getItem', 'adminLink').then((link) => {
        adminLink = link
    })
})

describe('Creating a wish list', () => {

    it('can add wish list items', () => {
        cy.visit(adminLink)

        cy.get('.view-participant-link').first().click()
        cy.contains('Your wishlist')
        cy.get('#wishlist-add-item').click()
        cy.get('#wishlist_wishlistItems_0_description').type(party.wishlist[0].itemName)
        cy.get('#wishlist-add-confirm').click()
        cy.contains('Item successfully added to your wishlist.')

        cy.visit(adminLink)
        cy.contains('Yes')  // Participant has specified wish list
    })
})
