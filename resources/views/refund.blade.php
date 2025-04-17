<!-- refund view -->
@extends('layouts.app')
@section('content')

<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Refund') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('refund') }}" method="POST" class="login-form" id="recaptchaForm">
                            @csrf
                            <div class="login-inner-block">
                                <div class="frm-grp">
                                    <label>@lang('Transaction Id')</label>
                                    <input type="text" name="transactionId"   placeholder="@lang('Example: OIB9FQP9H7')">
                                </div>
                                <div class="frm-grp">
                                    <label>@lang('Refund Amount')</label>
                                    <input type="text" name="amount" placeholder="@lang('Example: 100')">
                                </div>
                                <div class="frm-grp">
                                    <label>@lang('Refund Reason')</label>
                                    <textarea name="reason" placeholder="@lang('Example: Product not received')"></textarea>
                                </div>
                                <div class="frm-grp">
                                    <label>@lang('Refund Method')</label>
                                    <select name="method">
                                        <option value="bank">@lang('Bank Transfer')</option>
                                        <option value="paypal">@lang('PayPal')</option>
                                        <option value="stripe">@lang('Stripe')</option>
                                    </select>
                                </div>
                                <div class="frm-grp">
                                    <label>@lang('Refund Status')</label>
                                    <select name="status">
                                        <option value="pending">@lang('Pending')</option>
                                        <option value="completed">@lang('Completed')</option>
                                        <option value="failed">@lang('Failed')</option>
                                    </select>