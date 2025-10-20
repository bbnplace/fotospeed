<template>
    <!-- Shop Details Section Start -->
    <section class="shop-details-section section-padding">
        <div class="container">
        <div class="shop-details-wrapper">
            <div class="row">
                <div class="col-lg-5">
                    <div class="shop-details-image">
                    <div class="tab-content">
                        <div :id="`thumb${photo.id}`" :class="`tab-pane fade${photo.id == primaryPhoto.id ? ' show active ' : ''}`" v-for="photo in productPhotos" :key="photo.id">
                            <div class="shop-thumb">
                                <img :src="route('media.view', photo.id)" alt="img">
                            </div>
                        </div>
                        </div>
                        <ul class="nav mb-5">
                        <li class="nav-item" v-for="photo in productPhotos" :key="photo.id">
                            <a :href="`#thumb${photo.id}`" data-bs-toggle="tab" :class="`nav-link ps-0${photo.id == primaryPhoto.id ? ' active' : ''}`">
                                <i class="fas fa-camera"></i>
                            <img :src="photo.thumbnail" alt="img">
                            </a>
                        </li>
                    </ul>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="product-details-content">
                        <h2 class="pb-3">{{  product.name }}</h2>
                        <div class="star pb-3">
                            <a href="#"> <i class="fas fa-star"></i></a>
                            <a href="#"><i class="fas fa-star"></i></a>
                            <a href="#"> <i class="fas fa-star"></i></a>
                            <a href="#"><i class="fas fa-star"></i></a>
                            <a href="#"><i class="fas fa-star"></i></a>
                            <span>(25 Customer Review)</span>
                        </div>
                        <p class="mb-3">
                            {{ product.description }}
                        </p>
                        <div class="price-list">
                            <b>Starting from</b> <h3>{{ formatMoney(product.starting_price) }}</h3>
                        </div>
                        <!-- <div class="cart-wrp">
                            <div class="cart-quantity">
                                <form id='myform' method='POST' class='quantity' action='#'>
                                    <input type='button' value='-' class='qtyminus minus'>
                                    <input type='text' name='quantity' value='0' class='qty'>
                                    <input type='button' value='+' class='qtyplus plus'>
                                </form>
                            </div>
                            <a href="shop-details.html" class="icon">
                                <i class="far fa-heart"></i>
                            </a>
                            <a href="shop-details.html" class="icon">
                                <i class="far fa-share"></i>
                            </a>
                        </div> -->
                        <div class="shop-btn">
                            <!-- <a href="shop-details.html" class="theme-btn">
                                <span> Add to cart</span>
                            </a> -->
                            <a :href="route('customer.logged-in', { product: product.slug})" class="theme-btn">
                                <span>Submit Similar Job</span>
                            </a>
                        </div>
                        <h6 class="details-info"><span>SKU:</span> <a href="shop-details.html">124224</a></h6>
                        <h6 class="details-info"><span>Categories:</span> <a href="shop-details.html">Crux Indoor Fast and Easy</a></h6>
                        <h6 class="details-info style-2"><span>Tags:</span> <a href="shop-details.html"> <b>accessories</b> <b>business</b></a></h6>
                    </div>
                </div>
            </div>
            <div class="single-tab">
                <ul class="nav mb-5">
                    <li class="nav-item">
                    <a href="#description" data-bs-toggle="tab" class="nav-link ps-0 active">
                        <h6>Description</h6>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="#additional" data-bs-toggle="tab" class="nav-link">
                        <h6>Additional Information  </h6>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="#review" data-bs-toggle="tab" class="nav-link">
                        <h6>reviews (3)</h6>
                    </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="description" class="tab-pane fade show active">
                    <div class="description-items">
                        <div class="description-content">
                            <h3>Product descriptions</h3>
                            <p class="mb-4">
                                {{ product.description }}
                            </p>
                            <div class="description-list-items d-flex justify-content-between">
                                <ul class="description-list">
                                <li>
                                    Model wears:
                                    <span>UK 10/ EU 38/ US 6</span>
                                </li>
                                <li>
                                    Occasion:
                                    <span> Lifestyle, Sport</span>
                                </li>
                                <li>
                                    Country:
                                    <span>Italy</span>
                                </li>
                                </ul>
                                <ul class="description-list">
                                <li>
                                    Model wears:
                                    <span>UK 10/ EU 38/ US 6</span>
                                </li>
                                <li>
                                    Occasion:
                                    <span> Lifestyle, Sport</span>
                                </li>
                                <li>
                                    Country:
                                    <span>Italy</span>
                                </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div id="additional" class="tab-pane fade">
                    <div class="table-responsive mb-15">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                <td>Weight</td>
                                <td>240 Ton</td>
                                </tr>
                                <tr>
                                <td>Dimensions</td>
                                <td>20 × 30 × 40 cm</td>
                                </tr>
                                <tr>
                                <td>Colors</td>
                                <td>Black, Blue, Green</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div id="review" class="tab-pane fade">
                    <div class="review-items">
                        <div class="admin-items d-flex flex-wrap flex-md-nowrap align-items-center pb-4">
                            <div class="admin-img pb-4 pb-md-0 me-4">
                                <img :src="`${site.url}/assets/img/shop/r-1.jpg`" alt="image">
                            </div>
                            <div class="content p-4">
                                <div class="head-content pb-1 d-flex flex-wrap justify-content-between">
                                <h5>miklos salsa<span>27June 2025 at 5.44pm</span></h5>
                                <div class="star">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                </div>
                                <p>
                                Lorem ipsum dolor sit amet consectetur adipiscing elit. Curabitur vulputate vestibulum Phasellus rhoncus dolor eget viverra pretium.Curabitur vulputate vestibulum Phasellus rhoncus dolor eget viverra pretium.
                                </p>
                            </div>
                        </div>
                        <div class="admin-items d-flex flex-wrap flex-md-nowrap align-items-center pb-4">
                            <div class="admin-img pb-4 pb-md-0 me-4">
                                <img :src="`${site.url}/assets/img/shop/r-2.jpg`" alt="image">
                            </div>
                            <div class="content p-4">
                                <div class="head-content pb-1 d-flex flex-wrap justify-content-between">
                                <h5>Ethan Turner <span>27June 2025 at 5.44pm</span></h5>
                                <div class="star">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                </div>
                                <p>
                                Lorem ipsum dolor sit amet consectetur adipiscing elit. Curabitur vulputate vestibulum Phasellus rhoncus dolor eget viverra pretium.Curabitur vulputate vestibulum Phasellus rhoncus dolor eget viverra pretium.
                                </p>
                            </div>
                        </div>
                        <div class="admin-items d-flex flex-wrap flex-md-nowrap align-items-center pb-4">
                            <div class="admin-img pb-4 pb-md-0 me-4">
                                <img :src="`${site.url}/assets/img/shop/r-3.png`" alt="image">
                            </div>
                            <div class="content p-4">
                                <div class="head-content pb-1 d-flex flex-wrap justify-content-between">
                                <h5>Devid Miller<span>27June 2025 at 5.44pm</span></h5>
                                <div class="star">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                </div>
                                <p>
                                Lorem ipsum dolor sit amet consectetur adipiscing elit. Curabitur vulputate vestibulum Phasellus rhoncus dolor eget viverra pretium.Curabitur vulputate vestibulum Phasellus rhoncus dolor eget viverra pretium.
                                </p>
                            </div>
                        </div>
                        <div class="review-title mt-5 py-15 mb-30">
                            <h4>add a review</h4>
                            <div class="rate-now d-flex align-items-center">
                                <p>Rate this product? *</p>
                                <div class="star">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <div class="review-form">
                            <form action="#" id="contact-form" method="POST">
                                <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="form-clt">
                                        <input type="text" name="name" id="name" placeholder="Full Name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-clt">
                                        <input type="text" name="email" id="email" placeholder="email addres">
                                    </div>
                                </div>
                                <div class="col-lg-12 wow fadeInUp" data-wow-delay=".8">
                                    <div class="form-clt-big form-clt">
                                        <textarea name="message" id="message" placeholder="message"></textarea> 
                                    </div>
                                </div>
                                <div class="col-lg-6 wow fadeInUp" data-wow-delay=".9">
                                    <button type="submit" class="theme-btn">
                                        <span>Post Submit</span>
                                    </button>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </section>
</template>
<script setup>
import { usePage } from '@inertiajs/vue3';
import { usePrimaryPhoto, useFormatMoney } from '../Composables/usePrimaryPhoto';
const site = usePage().props.site;
const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});
const product = props.product;

const { formatMoney } = useFormatMoney();

const { getPrimaryPhotoData, getProductPhotos } = usePrimaryPhoto();
const primaryPhoto = getPrimaryPhotoData(product);  
const productPhotos = getProductPhotos(product);
</script>