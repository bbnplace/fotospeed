@extends('marketing.layouts.default')

@section('content') 

    <!-- Shop Cart Section Start -->
    <section class="signin-area section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-7">
                <div class="signin-item">
                    <h3>Sign In to Your Account</h3>
                    <form action="#">
                        <label for="email">Email</label>
                        <input id="email" type="email" placeholder="Enter your name here">
                        <label for="password">Password</label>
                        <input id="password" type="password" placeholder="Enter your password">
                        <a href="#0" class="theme-btn w-100 text-center">Sign in</a>
                    </form>
                    <div class="info text-center">
                        <p class="line1">Or <a href="reset-password.html">Forgot Password?</a></p>
                        <p class="line2">Don’t have an account? <a href="{{ route('signup') }}">SIGN UP</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>


@endsection