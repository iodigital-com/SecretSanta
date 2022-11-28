describe('Throwing a party', () => {
    let party = {
        date: (() => {
            let date = new Date()
            date.setMonth(date.getMonth() + 1)

            return date;
        })(),
        amount: '€25',
        location: 'iO Office',
        alternative_amount: '€50',
        alternative_location: 'Another location'
    }

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

    it('can define a party host', () => {
        cy.get('#party_participants_0_name').type('Host name')
        cy.get('#party_participants_0_email').type('host@iodigital.com')
    });

    it('can define participants', () => {
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

    let adminLink = undefined
    it('can submit party', () => {
        cy.get('#party_confirmed').check()
        cy.get('#create-party-btn').focus()
        cy.get('#create-party-btn').click()
        cy.get('#create-party-btn').click()
        cy.contains('Only 1 step to go')

        cy.get('#party-admin-link').then(($linkEl) => {
            adminLink = $linkEl.attr('href')
        })
    })

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

    it('can start the party', () => {
        cy.get('.btn-create-party').first().click()
        cy.contains('We started your party')
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

    it('can track participant view status', () => {
        cy.reload()

        cy.get('.view-participant-link').first().click()
        cy.contains('Your assigned buddy')
        cy.visit(adminLink)
        cy.contains('Viewed party')
    })

    it('can specify participant wish list', () => {
        cy.reload()

        cy.get('.view-participant-link').first().click()
        cy.contains('Your wishlist')
        cy.get('#wishlist-add-item').click()
        cy.get('#wishlist_wishlistItems_0_description').type('Something I want')
        cy.get('#wishlist-add-confirm').click()
        cy.contains('Item successfully added to your wishlist.')

        cy.visit(adminLink)
        cy.contains('Yes')  // Participant has specified wish list
    })

    it('can show an admin all wishlist contents', () => {
        cy.get('#btn_expose_wishlists').click()
        cy.contains('All Secret Santa Wishlists')
        cy.contains('Something I want')
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

    it('can delete the party', () => {
        cy.visit(adminLink)

        cy.get('#btn_delete').click()
        cy.get('input#delete-confirmation').type('delete everything')
        cy.get('#btn_delete_confirmation').click()

        cy.contains('Your Secret Santa list was deleted')
    })
})
