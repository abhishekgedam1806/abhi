@if($packages->count())
<div class="paypackages"> 
    <!---four-paln-->
    <div class="four-plan">
        <h3>{{__('Upgrade Package')}}</h3>
        <div class="row"> @foreach($packages as $package)
            <div class="col-md-4 col-sm-6 col-xs-12">
                <ul class="boxes">
                    <li class="plan-name">{{$package->package_title}}</li>
                    <li>
                        <div class="main-plan">
                            <div class="plan-price1-1">{{ $siteSetting->default_currency_code }}</div>
                            <div class="plan-price1-2">{{$package->package_price}}</div>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li class="plan-pages">{{__('Can post jobs')}} : {{$package->package_num_listings}}</li>
                    <li class="plan-pages">{{__('Package Duration')}} : {{$package->package_num_days}} {{__('Days')}}</li>
                    @if((bool)($siteSetting->is_razorpay_active ?? 1))
                    <li class="order" style="margin-bottom: 6px;">
                        <a href="{{route('payment.checkout', $package->id)}}" style="background: #2563EB; color: #FFFFFF; font-weight: 700; border-radius: 6px; padding: 10px 14px; display: block; text-align: center; text-decoration: none;">
                            <i class="fa fa-bolt" aria-hidden="true"></i> {{__('Upgrade with Razorpay / UPI')}}
                        </a>
                    </li>
                    @endif
                    @if((bool)$siteSetting->is_paypal_active)
                    <li class="order paypal"><a href="{{route('order.upgrade.package', $package->id)}}"><i class="fa fa-cc-paypal" aria-hidden="true"></i> {{__('pay with paypal')}}</a></li>
                    @endif
                    @if((bool)$siteSetting->is_stripe_active)
                    <li class="order"><a href="{{route('stripe.order.form', [$package->id, 'upgrade'])}}"><i class="fa fa-cc-stripe" aria-hidden="true"></i> {{__('pay with stripe')}}</a></li>
                    @endif
                </ul>
            </div>
            @endforeach </div>
    </div>
    <!---end four-paln--> 
</div>
@endif