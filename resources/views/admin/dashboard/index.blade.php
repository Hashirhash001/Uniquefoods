@extends('admin.layouts.app')

@section('title','Dashboard')

@section('content')
<div class="transection">
    <h3 class="title">Overview</h3>

    <div class="row g-5">
        <div class="col-xl-3">
            <div class="single-over-fiew-card">
                <span>Revenue</span>
                <h2>${{ number_format($revenue,2) }}</h2>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="single-over-fiew-card">
                <span>Orders</span>
                <h2>{{ $orders }}</h2>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="single-over-fiew-card">
                <span>Products</span>
                <h2>{{ $products }}</h2>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="single-over-fiew-card">
                <span>Users</span>
                <h2>{{ $users }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection
