@extends('admin::layouts.admin')

@section('title', __('admin::translation.upgrade'))

@section('content')
    <section class="py-5" id="pricing-plans">
        <div class="container">
            <div class="row">
                <!-- Free Tier -->
                @foreach($plans as $plan)
                    <div class="col-lg-4">
                        <div class="card mb-5 mb-lg-0">
                            <div class="card-body">
                                <h5 class="card-title text-muted text-uppercase text-center">{{ $plan->name }}</h5>
                                <h6 class="card-price text-center">${{ $plan->price }}<span class="period">/month</span>
                                </h6>
                                <hr>
                                <ul class="fa-ul">
                                    @foreach($features as $feature)
                                        @php
                                            $value = $plan->getFeatureValue($feature->name)
                                        @endphp
                                        <li class="@if(!$value) text-muted @endif">
                                            @if($value)
                                                <span class="fa-li"><i class="fas fa-check-circle"></i></span>
                                            @else
                                                <span class="fa-li"><i class="fas fa-times-circle"></i></span>
                                            @endif

                                            {{ $feature->getLabelWithValue($value) }}

                                            @if(!empty($feature->description))
                                                <span class="helper-tooltip" data-toggle="tooltip" data-placement="top"
                                                      title="{{ $feature->description }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="d-grid align-content-center text-center">
                                    <button
                                            type="button"
                                            class="btn btn-primary text-uppercase choose-plan"
                                            data-id="{{ $plan->id }}"
                                            @disabled($plan->is_free || $currentPlan?->id === $plan->id)
                                    >
                                        {{ __('admin::translation.choose_plan') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('subscription.js') }}"></script>

    <div class="modal fade" id="subscription" tabindex="-1" role="dialog" aria-labelledby="subscriptionLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post"
                  action="{{ route('subscription.subscribe', ['network']) }}"
                  data-success="handlePaymentSuccess"
                  id="subscription-form"
            >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="subscriptionLabel">{{ __('admin::translation.payment') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="payment-container">
                            {{ Field::select(__('admin::translation.method'), 'method_id')->dropDownList($paymentMethods) }}

                            <input type="hidden" id="plan_id" name="plan_id" value="">
                            <input type="hidden" name="token"
                                   value="{{ encrypt(['billable_id' => $websiteId, 'billable_type' => \Juzaweb\Modules\Admin\Models\Website::class]) }}">

                            <div id="form-card"></div>

                            <div id="payment-message"></div>

                            <button type="submit" class="btn btn-primary">{{ __('admin::translation.payment') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script nonce="{{ csp_script_nonce() }}">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();

            const subscription = new SubscriptionForm('network', '#subscription-form');

            $('.choose-plan').on('click', function () {
                const planId = $(this).data('id');
                $('#plan_id').val(planId);
                $('#subscription').modal('show');
            });
        })
    </script>
@endsection
