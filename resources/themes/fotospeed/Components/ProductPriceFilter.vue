<template>
    <div class="single-sidebar-widget">
        <div class="wid-title">
            <h4>Filter By Price</h4>
        </div>
        <div class="range__barcustom">
            <div class="slider">
                <div class="progress" style="left: 25%; right: 25%;"></div>
            </div>
            <div class="range-input">
                <input type="range" class="range-min" min="0" max="10000" value="2500">
                <input type="range" class="range-max" min="100" max="10000" value="7500">
            </div>
            <div class="range-items">
                <div class="price-input d-flex">
                    <div class="field">
                        <span>$</span>
                        <input type="number" class="input-min" value="100">
                    </div>
                    <div class="separators">-</div>
                    <div class="field">
                        <span>$</span>
                        <input type="number" class="input-max" value="1000">
                    </div>
                    <a href="shop-left-sidebar.html" class="theme-btn border-radius-none">Filter</a>
                </div>
            </div>
        </div>
    </div>
</template>