@extends('admin::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin::translation.custom_domain') }}</h3>
                </div>
                <div class="card-body">
                    @if($website->domain)
                        <div class="alert alert-success">
                            <h5>{{ __('admin::translation.current_custom_domain') }}</h5>
                            <p class="mb-2">
                                <strong>{{ $website->domain }}</strong>
                            </p>
                            <p class="mb-0">
                                <small class="text-muted text-white">
                                    {{ __('admin::translation.your_website_is_accessible_at_url', ['url' => 'https://' . $website->domain]) }}
                                </small>
                            </p>
                        </div>

                        <form action="{{ admin_url('settings/domain') }}" method="post" class="form-ajax">
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> {{ __('admin::translation.remove_custom_domain') }}
                            </button>
                        </form>

                        <hr class="my-4">

                        <h5>{{ __('admin::translation.update_custom_domain') }}</h5>
                    @else
                        <div class="alert alert-info">
                            <p class="mb-0">
                                {{ __('admin::translation.you_have_not_set_up_a_custom_domain_yet_your_website_is_currently_accessible_at') }}
                                <strong>{{ $website->subdomain }}.{{ config('network.domain') }}</strong>
                            </p>
                        </div>
                    @endif

                    <form action="{{ admin_url('settings/domain') }}" method="post" class="form-ajax">
                        <div class="row">
                            <div class="col-md-8">
                                {{ Field::text(__('admin::translation.domain'), 'domain', [
                                    'value' => $website->domain,
                                    'placeholder' => 'example.com',
                                    'help_text' => __('admin::translation.enter_your_custom_domain_the_domain_must_use_cloudflare_nameservers')
                                ]) }}
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-8">
                                {{ Field::checkbox(__('admin::translation.redirect_subdomain_to_custom_domain'), 'redirect_to_domain', [
                                    'value' => setting('redirect_to_domain', 0),
                                    'help_text' => __('admin::translation.automatically_redirect_visitors_from_subdomain_to_domain', [
                                        'subdomain' => $website->subdomain . '.' . (config('network.subsite_domain') ?: config('network.domain')),
                                        'domain' => $website->domain
                                    ])
                                ]) }}
                            </div>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <h6><i class="fas fa-exclamation-triangle"></i> {{ __('admin::translation.important') }}</h6>
                            <ul class="mb-0">
                                <li>{{ __('admin::translation.your_domain_must_be_using_cloudflare_nameservers') }}</li>
                                <li>{{ __('admin::translation.point_your_domain_to_this_website_by_adding_a_cname_or_a_record_in_your_dns_settings') }}</li>
                                <li>{{ __('admin::translation.dns_propagation_can_take_up_to_48_hours') }}</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('admin::translation.save_custom_domain') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin::translation.how_to_set_up_your_custom_domain') }}</h3>
                </div>
                <div class="card-body">
                    <ol>
                        <li>
                            <strong>{{ __('admin::translation.add_your_domain_to_cloudflare') }}</strong>
                            <p>{{ __('admin::translation.if_your_domain_is_not_already_on_cloudflare_sign_up_at_cloudflarecom_and_add_your_domain') }}</p>
                        </li>
                        <li>
                            <strong>{{ __('admin::translation.update_your_nameservers') }}</strong>
                            <p>{{ __('admin::translation.change_your_domains_nameservers') }}</p>
                        </li>
                        <li>
                            <strong>{{ __('admin::translation.configure_dns') }}</strong>
                            <p>{{ __('admin::translation.in_cloudflares_dns_settings_add_cname_record') }}</p>
                            <code>dns.{{ config('network.domain') }}</code>
                        </li>
                        <li>
                            <strong>{{ __('admin::translation.enter_your_domain_above') }}</strong>
                            <p>{{ __('admin::translation.once_your_domain_is_on_cloudflare_enter_it_in_the_form_above_and_click_save') }}</p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
