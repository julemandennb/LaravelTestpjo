
import { router } from '@inertiajs/vue3';

export function addToCart(produkt, quantity = 1) {

    router.post(
        route('shop.cart.store'),
        {
            produktID: produkt.produktID ?? produkt.id,
            quantity: quantity,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                console.log('Product added to cart!');
            },

            onError: (errors) => {
                console.error('Could not add product:', errors);
            },
        }
    );
}
