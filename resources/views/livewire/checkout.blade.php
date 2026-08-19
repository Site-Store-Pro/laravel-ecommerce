<div x-data="{}" 
     x-init="@if(!empty($gaEcommerceData)) if(typeof window.trackGaEvent === 'function') { window.trackGaEvent('begin_checkout', {{ json_encode($gaEcommerceData) }}); } @endif"
     class="pt-4 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 mb-12">@label('checkout.page_heading', 'Checkout')</h1>

        <!-- Flash Status Message -->
        @if(session()->has('status'))
            <div class="mb-8 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-8 p-4 bg-red-50 rounded-2xl border border-red-100 flex items-center gap-3 text-red-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if(session()->has('info'))
            <div class="mb-8 p-4 bg-amber-50 rounded-2xl border border-amber-100 flex items-center gap-3 text-amber-800 text-sm font-semibold">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('info') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left Side: Shipping & Payment -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Guest Checkout / Account Promotion Card -->
                @if(!auth()->check())
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <h2 class="text-lg font-bold text-slate-900">@label('checkout.returning_customer_heading', 'Returning Customer?')</h2>
                            <p class="text-xs text-slate-400 mt-1">@label('checkout.returning_customer_message', 'Sign in to check out faster with your saved details, or continue below to order as a guest.')</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                            @php
                                $enabledProviders = array_filter([
                                    'google' => config('services.google.client_id'),
                                    'facebook' => config('services.facebook.client_id'),
                                    'github' => config('services.github.client_id'),
                                ]);
                                $hasSocial = count($enabledProviders) > 0;
                            @endphp

                            <!-- Email / Password Login Form -->
                            <div class="{{ $hasSocial ? 'md:col-span-7' : 'md:col-span-12' }} space-y-4">
                                <h3 class="text-sm font-bold text-slate-800">@label('checkout.sign_in_heading', 'Sign In to Your Account')</h3>
                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_email', 'Email Address')</label>
                                        <input type="email" wire:model="loginEmail" placeholder="@label('checkout.field_email_placeholder', 'you@example.com')" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                        @error('loginEmail') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_password', 'Password')</label>
                                        <input type="password" wire:model="loginPassword" placeholder="••••••••" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                        @error('loginPassword') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                @error('login_error')
                                    <div class="text-xs text-red-600 font-semibold bg-red-50 border border-red-100 p-2.5 rounded-xl text-red-800">{{ $message }}</div>
                                @enderror

                                <button type="button" wire:click.prevent="loginUser" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-md transition duration-150">
                                    @label('checkout.sign_in_continue', 'Sign In & Continue')
                                </button>
                            </div>

                            @if($hasSocial)
                                <!-- Divider (Col 1) -->
                                <div class="hidden md:flex md:col-span-1 justify-center items-center h-full">
                                    <div class="border-l border-slate-200 h-28"></div>
                                </div>

                                <!-- Social Buttons (Col 4) -->
                                <div class="md:col-span-4 space-y-4">
                                    <h3 class="text-sm font-bold text-slate-800">@label('checkout.or_continue_with', 'Or continue with')</h3>
                                    <div class="flex flex-col gap-2.5">
                                        @if(config('services.google.client_id'))
                                            <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center gap-2 border border-slate-200 bg-white hover:bg-slate-50 py-2.5 rounded-xl text-xs font-bold text-slate-700 shadow-sm transition">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                                </svg>
                                                Google
                                            </a>
                                        @endif

                                        @if(config('services.facebook.client_id'))
                                            <a href="{{ route('social.redirect', 'facebook') }}" class="flex items-center justify-center gap-2 border border-slate-200 bg-white hover:bg-slate-50 py-2.5 rounded-xl text-xs font-bold text-slate-700 shadow-sm transition">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="#1877F2">
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                                Facebook
                                            </a>
                                        @endif

                                        @if(config('services.github.client_id'))
                                            <a href="{{ route('social.redirect', 'github') }}" class="flex items-center justify-center gap-2 border border-slate-200 bg-white hover:bg-slate-50 py-2.5 rounded-xl text-xs font-bold text-slate-700 shadow-sm transition">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.9-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.9 1.52 2.34 1.07 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0012 2z"/>
                                                </svg>
                                                GitHub
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="saveDetailsAndContinue" class="space-y-6">
                    <!-- Customer / Shipping details Card -->
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm animate-fade-in">
                        <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">
                            @if($requiresShipping) @label('checkout.shipping_details', 'Shipping Details') @else @label('checkout.customer_info', 'Customer Info') @endif
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider font-sans">@label('checkout.field_full_name', 'Full Name')</label>
                                <input type="text" wire:model="name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                @error('name') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_email', 'Email Address')</label>
                                <input type="email" wire:model="email" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                @error('email') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            @if(!$hideCompanyField)
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_company', 'Company (Optional)')</label>
                                <input type="text" wire:model="company" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                            </div>
                            @endif

                            @if($requiresShipping)
                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_address1', 'Address Line 1')</label>
                                    <input type="text" wire:model="shipping_address1" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    @error('shipping_address1') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_address2', 'Address Line 2 (Optional)')</label>
                                    <input type="text" wire:model="shipping_address2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_city', 'City')</label>
                                    <input type="text" wire:model="shipping_city" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    @error('shipping_city') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_postal', 'Postal / ZIP Code')</label>
                                    <input type="text" wire:model="shopping_postalcode" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                    @error('shopping_postalcode') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_country', 'Country')</label>
                                    <select wire:model.live="shipping_countrycode" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        <option value="">@label('checkout.field_country_placeholder', 'Select Country')</option>
                                        @foreach($countries as $c)
                                            <option value="{{ $c->code }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('shipping_countrycode') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                @if($shipping_countrycode === 'US' || $shipping_countrycode === 'CA')
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">
                                            @if($shipping_countrycode === 'US') @label('checkout.field_state', 'State') @else @label('checkout.field_province', 'Province / Territory') @endif
                                        </label>
                                        <select wire:model="shipping_state" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                            <option value="">@if($shipping_countrycode === 'US') @label('checkout.field_state_placeholder', 'Select State') @else @label('checkout.field_province_placeholder', 'Select Province') @endif</option>
                                            @foreach($states as $s)
                                                <option value="{{ $s->code }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('shipping_state') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                @else
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_region', 'State / Region (Optional)')</label>
                                        <input type="text" wire:model="shipping_state" placeholder="@label('checkout.field_region_placeholder', 'e.g. London')" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        @error('shipping_state') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            @endif

                            @if(!auth()->check())
                                <div class="md:col-span-2 border-t border-slate-100 pt-4 mt-2">
                                    <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">@label('checkout.field_password_optional', 'Create a Password (Optional)')</label>
                                    <p class="text-xs text-slate-400 mb-2">@label('checkout.field_password_optional_message', 'Provide a password if you want to register and speed through future purchases.')</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_password', 'Password')</label>
                                            <input type="password" wire:model="password" placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                            @error('password') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">@label('checkout.field_confirm_password', 'Confirm Password')</label>
                                            <input type="password" wire:model="password_confirmation" placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- ═══ Checkout Custom Fields (position = checkout) ═══ --}}
                            @php
                                $checkoutFields = \App\Models\CheckoutCustomField::where('position','checkout')->where('is_active',true)->orderBy('sort_order')->get();
                                $checkoutOptinMode     = \App\Models\CmsSetting::get('checkout_optin_mode','off');
                                $checkoutOptinLabel    = \App\Models\CmsSetting::get('checkout_optin_label','Yes, add me to the mailing list');
                                $checkoutOptinPosition = \App\Models\CmsSetting::get('checkout_optin_position','checkout');
                            @endphp

                            @if($checkoutFields->isNotEmpty())
                                <div class="md:col-span-2 border-t border-slate-100 pt-5 mt-2 space-y-4">
                                    @foreach($checkoutFields as $idx => $cf)
                                        <div>
                                            @if($cf->html_above)
                                                <div class="prose prose-sm text-slate-600 mb-2">{!! $cf->html_above !!}</div>
                                            @endif
                                            <label class="text-xs font-bold text-slate-500 block mb-1 uppercase tracking-wider">
                                                {{ $cf->label }}
                                                @if($cf->is_required)<span class="text-red-500 ml-0.5">*</span>@endif
                                            </label>
                                            @if($cf->instructions)
                                                <p class="text-[11px] text-slate-400 mb-1">{{ $cf->instructions }}</p>
                                            @endif

                                            @if($cf->type === 'textarea')
                                                <textarea wire:model="checkoutCustomData.{{ $idx }}" rows="3"
                                                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm"></textarea>
                                            @elseif($cf->type === 'select')
                                                <select wire:model="checkoutCustomData.{{ $idx }}"
                                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                                                    <option value="">@label('checkout.select_placeholder', '— Select —')</option>
                                                    @foreach($cf->options ?? [] as $opt)
                                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($cf->type === 'radio')
                                                <div class="flex flex-wrap gap-3">
                                                    @foreach($cf->options ?? [] as $opt)
                                                        <label class="flex items-center gap-2 cursor-pointer">
                                                            <input type="radio" wire:model="checkoutCustomData.{{ $idx }}" value="{{ $opt }}" class="text-indigo-600">
                                                            <span class="text-sm text-slate-700">{{ $opt }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @elseif($cf->type === 'checkbox')
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" wire:model="checkoutCustomData.{{ $idx }}" class="w-4 h-4 text-indigo-600 rounded">
                                                    <span class="text-sm text-slate-700">{{ $cf->label }}</span>
                                                </label>
                                            @elseif($cf->type === 'checkbox_group')
                                                <div class="flex flex-wrap gap-3">
                                                    @foreach($cf->options ?? [] as $opt)
                                                        <label class="flex items-center gap-2 cursor-pointer">
                                                            <input type="checkbox" wire:model="checkoutCustomData.{{ $idx }}" value="{{ $opt }}" class="w-4 h-4 text-indigo-600 rounded">
                                                            <span class="text-sm text-slate-700">{{ $opt }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @else
                                                <input type="text" wire:model="checkoutCustomData.{{ $idx }}"
                                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 text-sm">
                                            @endif
                                            @error("checkoutCustomData.{$idx}")
                                                <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Manual opt-in checkbox at checkout step --}}
                            @if($checkoutOptinMode === 'manual' && $checkoutOptinPosition === 'checkout')
                                <div class="md:col-span-2 mt-2">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" wire:model="checkoutOptIn" id="checkout-optin-checkbox"
                                               class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                        <span class="text-sm text-slate-600 group-hover:text-slate-800 transition">{{ $checkoutOptinLabel }}</span>
                                    </label>
                                </div>
                            @endif

                            <div class="md:col-span-2 mt-6 border-t border-slate-100 pt-6">
                                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-md hover:scale-[1.01] transition duration-150 flex items-center justify-center gap-2">
                                    @label('checkout.continue_to_review', 'Continue to Review')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Side: Order Review -->
            <div class="lg:col-span-4 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 mb-6">@label('checkout.order_details', 'Order Details')</h2>

                <!-- Items list -->
                <div class="space-y-4 max-h-60 overflow-y-auto">
                    @foreach($items as $item)
                        <div class="flex items-center justify-between text-sm gap-4">
                            <div class="flex-1">
                                <span class="font-semibold text-slate-800">{{ $item->item_name }}</span>
                                <span class="text-xs text-slate-400 block">@label('checkout.qty_label', 'Qty:') {{ number_format($item->item_qty, 0) }}</span>
                                @if($item->item_shippable)
                                    <span class="inline-block bg-indigo-50 text-indigo-700 text-[10px] px-1.5 py-0.5 rounded font-bold mt-1">@label('checkout.requires_shipping', 'Requires Shipping')</span>
                                @elseif(!empty($item->is_digital) || !empty($item->download_item))
                                    <span class="inline-block bg-teal-50 text-teal-700 text-[10px] px-1.5 py-0.5 rounded font-bold mt-1">@label('checkout.digital_delivery', 'Digital Delivery')</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-slate-950 block">${{ number_format($item->item_price * $item->item_qty, 2) }}</span>
                                @if($item->item_discount_price > 0)
                                    <span class="line-through text-slate-400 text-[10px] block">${{ number_format(($item->item_price + $item->item_discount_price) * $item->item_qty, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Coupon Entry Form -->
                <div class="border-t border-slate-100 pt-6 mt-6 space-y-3">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">@label('checkout.coupon_heading', 'Promo / Coupon Code')</h3>
                    @if($activeCoupon)
                        <div class="flex items-center justify-between bg-emerald-50 border border-emerald-100 rounded-2xl px-4 py-2 text-xs">
                            <span class="font-bold text-emerald-800">@label('checkout.coupon_active', 'Coupon Active:') {{ $activeCoupon }}</span>
                            <button type="button" wire:click="removeCoupon" class="text-rose-600 hover:text-rose-800 font-bold">@label('checkout.coupon_remove', 'Remove')</button>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <input type="text" wire:model="couponCode" placeholder="@label('checkout.coupon_placeholder', 'Enter coupon...')" class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                            <button type="button" wire:click="applyCoupon" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow transition duration-150">
                                @label('checkout.coupon_apply', 'Apply')
                            </button>
                        </div>
                        @error('couponCode') <span class="text-xs text-rose-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                    @endif
                </div>

                <!-- Order Totals -->
                <div class="border-t border-slate-100 pt-6 mt-6 space-y-4">
                    <div class="flex justify-between text-sm text-slate-500">
                        <span>@label('checkout.subtotal', 'Subtotal')</span>
                        <span class="font-semibold text-slate-850">${{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($total_discount > 0)
                        @foreach($discounts as $disc)
                            <div class="flex justify-between text-xs text-emerald-600 font-semibold">
                                <span>@label('checkout.discount', 'Discount') ({{ $disc['name'] }})</span>
                                <span>-${{ number_format($disc['amount'], 2) }}</span>
                            </div>
                        @endforeach
                    @endif

                    <div class="flex justify-between text-sm text-slate-500 border-t border-slate-100 pt-3">
                        <span>@label('checkout.shipping', 'Shipping')</span>
                        <span class="font-semibold text-emerald-600">@label('checkout.shipping_calculated', 'Calculated at review')</span>
                    </div>

                    <div class="border-t border-slate-100 pt-4 flex justify-between text-lg font-extrabold text-slate-900">
                        <span>@label('checkout.total', 'Total')</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
