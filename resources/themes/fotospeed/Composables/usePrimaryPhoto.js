import { usePage } from '@inertiajs/vue3';
import { get } from 'jquery';

export function usePrimaryPhoto() {
    const site = usePage().props.site;

    const getProductPhotos = (product) => {
        if (!product.product_photos) return [];
        let photos;
        try {
            photos = JSON.parse(product.product_photos);
        } catch {
            return [];
        }
        if (!photos.images) return [];
        return photos.images.map(img => ({
            id: img.id,
            thumbnail: img.thumbnail || img.thumbnail_100 || `${site.url}/assets/img/shop/01.jpg`
        }));
    };

    const getPrimaryPhoto = (product) => {
        if (!product.product_photos) return `${site.url}/assets/img/shop/01.jpg`;
        let photos;
        try {
            photos = JSON.parse(product.product_photos);
        } catch {
            return `${site.url}/assets/img/shop/01.jpg`;
        }
        if (!photos.images || !photos.primaryPhotoId) return `${site.url}/assets/img/shop/01.jpg`;
        const primary = photos.images.find(img => img.id === photos.primaryPhotoId);
        return primary?.thumbnail || primary?.thumbnail_100 || `${site.url}/assets/img/shop/01.jpg`;
    };

    const getPrimaryPhotoData = (product) => {
         if (!product.product_photos) return `${site.url}/assets/img/shop/01.jpg`;
        let photos;
        try {
            photos = JSON.parse(product.product_photos);
        } catch {
            return `${site.url}/assets/img/shop/01.jpg`;
        }
        if (!photos.images || !photos.primaryPhotoId) return `${site.url}/assets/img/shop/01.jpg`;
        const primary = photos.images.find(img => img.id === photos.primaryPhotoId);
        return primary;
    };

    return { getPrimaryPhoto, getProductPhotos, getPrimaryPhotoData };
}


export function useFormatMoney() {
    const site = usePage().props.site;

    const formatMoney = (amount) => {
        if (typeof amount !== 'number') return '0.00';
        return new Intl.NumberFormat(site.locale, {
            style: 'currency',
            currency: site.currencyCode,
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    };

    return { formatMoney }
}