@extends('frontend.layouts.app')
@section('title', 'Verify Your Email - Unique Foods')
@section('content')
<div class="unique-auth-wrapper">
    <div style="text-align:center; padding: 60px 20px;">
        <h2>📧 Verify Your Email</h2>
        <p>We've sent a verification link to your email. Please check your inbox.</p>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="unique-btn-primary">Resend Verification Email</button>
        </form>
    </div>
</div>
@endsection
