export function formatPrice(price) {

    let newprice = price / 100

    return new Intl.NumberFormat('da-DK', {
        style: 'currency',
        currency: 'DKK',
    }).format(Number(newprice));
}
