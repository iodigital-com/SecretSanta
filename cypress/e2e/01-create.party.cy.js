import {party} from './fixtures/a_party.js'

describe('Creating a party', () => {

    it('should say "What is Secret Santa?" to unknown', () => {
        cy.visit('/')
        cy.contains('What is Secret Santa?')
    })

    it('can specify a party date', () => {
        let dateAsString = party.date.toLocaleDateString('en-UK')

        cy.get('#party_eventdate')
            .type(`${dateAsString}{enter}`)
            .should('have.value', dateAsString.replaceAll('/', '-'))
    });

    it('can specify a party location', () => {
        cy.get('#party_location').type(`${party.location}{enter}`)
    });

    it('can specify an amount to spend', () => {
        cy.get('#party_amount').type(`${party.amount}{enter}`)
    });

    it('can specify a party host', () => {
        cy.get('#party_participants_0_name').type('Host name')
        cy.get('#party_participants_0_email').type('host@iodigital.com')
    });

    it('can specify participants', () => {
        for (let i = 1; i <= 4; i++) {
            if (i > 2) {
                cy.get('.add-new-participant').first().click();
            }

            cy.get('#party_participants_'+i+'_name').type('Participant '+i);
            cy.get('#party_participants_'+i+'_email').type('participant.'+i+'@iodigital.com');
        }
    })

    it('can specify a personal message', () => {
        cy.get('#party_message').type('Time for a party')
    })

    it('can submit party', () => {
        cy.get('#party_confirmed').check()
        cy.get('#create-party-btn').focus()
        cy.get('#create-party-btn').click()
        cy.get('#create-party-btn').click()
        cy.contains('Only 1 step to go')

        cy.get('#party-admin-link').then(($linkEl) => {
            let adminLink = $linkEl.attr('href')

            cy.task('setItem', {name: 'adminLink', value: adminLink})  // Make admin link available to subsequent specs
        })
    })
})
