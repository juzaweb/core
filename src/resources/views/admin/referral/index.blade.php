@extends('core::layouts.admin')

@section('content')
    <div class="row mt-3">
        <div class="col-md-12">
            <x-card title="{{ __('core::translation.my_referral_information') }}">
                @if($user->referral_code)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('core::translation.referral_code') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $user->referral_code }}" readonly id="referral-code">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button" data-target="#referral-code">
                                            <i class="fas fa-copy"></i> {{ __('core::translation.copy') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('core::translation.referral_link') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ home_url("?ref={$user->referral_code}") }}" readonly id="referral-link">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" type="button" data-target="#referral-link">
                                            <i class="fas fa-copy"></i> {{ __('core::translation.copy') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="mb-3 text-muted">{{ __('core::translation.you_don't have a referral code yet. Generate one to start referring!') }}</p>
                        <form action="{{ admin_url('my-referrals/generate-code') }}" method="post" class="form-ajax">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm px-4">
                                <i class="fas fa-magic mr-2"></i> {{ __('core::translation.generate_referral_code') }}
                            </button>
                        </form>
                    </div>
                @endif
            </x-card>
        </div>

        <div class="col-md-12 mt-3">
            <x-card title="{{ __('core::translation.referred_users') }}">
                {{ $dataTable->table() }}
            </x-card>
        </div>
    </div>
@endsection

@section('scripts')
    {{ $dataTable->scripts(null, ['nonce' => csp_script_nonce()]) }}
    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        $(document).on('click', '.copy-btn', function() {
            var target = $(this).data('target');
            var input = $(target);
            input.select();
            document.execCommand("copy");

            var originalText = $(this).html();
            $(this).html('<i class="fas fa-check"></i> {{ __('core::translation.copied') }}');

            var $btn = $(this);
            setTimeout(function() {
                $btn.html(originalText);
            }, 2000);
        });
    </script>
@endsection
