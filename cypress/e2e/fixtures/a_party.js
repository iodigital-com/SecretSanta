export const party = {
  date: (() => {
    let date = new Date()
    date.setMonth(date.getMonth() + 1)

    return date;
  })(),
  amount: '€25',
  location: 'iO Office',
  alternative_amount: '€50',
  alternative_location: 'Another location',
  wishlist: [
    {'itemName': 'Something I want'}
  ]
};