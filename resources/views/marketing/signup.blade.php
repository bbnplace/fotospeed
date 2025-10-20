@extends('marketing.layouts.default')

@section('content')

<!-- Shop Cart Section Start -->
    <section class="signup-area fix section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-7">
                    <div class="signin-item">
                        <h3>Create Account</h3>
                        <form action="#">
                            <div class="row g-4">
                                <div class="col-6">
                                    <label for="first-name">First Name</label>
                                    <input id="first-name" type="text" placeholder="Enter your first name ">
                                </div>
                                <div class="col-6">
                                    <label for="last-name">Last Name</label>
                                    <input id="last-name" type="text" placeholder="Enter your last name">
                                </div>
                            </div>
                            <label for="email">Email</label>
                            <input id="email" type="email" placeholder="Enter your email">
                            <label for="password">Password</label>
                            <input id="password" type="password" placeholder="Enter your password">
                            <label for="confirm-password">Confirm Password</label>
                            <input id="confirm-password" type="password" placeholder="Enter your password">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label ps-2" for="flexCheckDefault">
                                    I agree to <a href="#0">Terms</a> and <a href="#0">policy</a>
                                </label>
                            </div>
                            <button class="theme-btn mt-40 w-100 text-center">Sign Up</button>
                            {{-- <span class="fs-18 text-center d-block my-4">Or sign up with</span>
                            <a href="#0" class="theme-btn google-btn w-100 text-center">
                                <svg class="me-1" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M22.8055 10.0415H22V10H13V14H18.6515C17.827 16.3285 15.6115 18 13 18C9.6865 18 7 15.3135 7 12C7 8.6865 9.6865 6 13 6C14.5295 6 15.921 6.577 16.9805 7.5195L19.809 4.691C18.023 3.0265 15.634 2 13 2C7.4775 2 3 6.4775 3 12C3 17.5225 7.4775 22 13 22C18.5225 22 23 17.5225 23 12C23 11.3295 22.931 10.675 22.8055 10.0415Z"
                                        fill="#FFC107" />
                                    <path
                                        d="M4.15039 7.3455L7.43589 9.755C8.32489 7.554 10.4779 6 12.9974 6C14.5269 6 15.9184 6.577 16.9779 7.5195L19.8064 4.691C18.0204 3.0265 15.6314 2 12.9974 2C9.15639 2 5.82539 4.1685 4.15039 7.3455Z"
                                        fill="#FF3D00" />
                                    <path
                                        d="M13.0021 22.0042C15.5851 22.0042 17.9321 21.0157 19.7066 19.4082L16.6116 16.7892C15.5739 17.5784 14.3058 18.0053 13.0021 18.0042C10.4011 18.0042 8.19261 16.3457 7.36061 14.0312L4.09961 16.5437C5.75461 19.7822 9.11561 22.0042 13.0021 22.0042Z"
                                        fill="#4CAF50" />
                                    <path
                                        d="M22.8055 10.0415H22V10H13V14H18.6515C18.2571 15.1082 17.5467 16.0766 16.608 16.7855L16.6095 16.7845L19.7045 19.4035C19.4855 19.6025 23 17 23 12C23 11.3295 22.931 10.675 22.8055 10.0415Z"
                                        fill="#1976D2" />
                                </svg>
                                Sign Up With Google
                            </a> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection