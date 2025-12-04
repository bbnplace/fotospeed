@extends('marketing.layouts.default')

@section('content')

<!-- Shop Cart Section Start -->
    <section class="signup-area fix section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-7">
                    <div class="signin-item">
                        <h3>Request an Account</h3>
                        <form action="{{ route('signup.store') }}" method="POST">
                            @csrf
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="name">Full Name</label>
                                    <input id="name" name="name" type="text" placeholder="Enter your full name" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-12">
                                    <label for="mobile">Mobile Number</label>
                                    <input id="mobile" name="mobile" type="text" placeholder="Enter your mobile number" value="{{ old('mobile') }}" required>
                                </div>
                                <div class="col-12">
                                    <label for="email">Email (Optional)</label>
                                    <input id="email" name="email" type="email" placeholder="Enter your email" value="{{ old('email') }}">
                                </div>
                                <div class="col-12">
                                    <label for="state">State</label>
                                    <select id="state" name="state" class="form-control" style="height: 50px; background-color: #f3f4f6; border: none; padding: 0 20px; border-radius: 5px; width: 100%;" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->name }}" {{ old('state') == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="intended_use">How do you intend to use the platform?</label>
                                    <textarea id="intended_use" name="intended_use" class="form-control" style="background-color: #f3f4f6; border: none; padding: 20px; border-radius: 5px; width: 100%;" rows="4" placeholder="Please describe how you intend to use the platform..." required>{{ old('intended_use') }}</textarea>
                                </div>
                            </div>
                            
                            <!-- <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                                <label class="form-check-label ps-2" for="flexCheckDefault">
                                    I agree to <a href="{{ route('privacy') }}">Terms</a> and <a href="{{ route('privacy') }}">policy</a>
                                </label>
                            </div> -->
                            <button class="theme-btn mt-40 w-100 text-center">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection