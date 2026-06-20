@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4 row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input id="privacy_policy" type="checkbox" class="form-check-input @error('privacy_policy') is-invalid @enderror" name="privacy_policy" value="1" required @checked(old('privacy_policy'))>
                                    <label for="privacy_policy" class="form-check-label">
                                        {{ __('I have read and accept the') }}
                                        <button type="button" class="btn btn-link p-0 align-baseline text-decoration-underline" data-bs-toggle="modal" data-bs-target="#privacypolicy">
                                            {{ __('Privacy Policy') }}
                                        </button>
                                    </label>

                                    @error('privacy_policy')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="privacypolicy" tabindex="-1" aria-labelledby="privacypolicyLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="privacypolicyLabel">{{ __('Privacy Policy') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Last updated:</strong> 19/06/2026</p>

                    <p>This Privacy Policy explains how personal data of the users of MatronaeDB (the "Service"), available at {{ url('/') }}, is processed, in accordance with Articles 13 and 14 of Regulation (EU) 2016/679 ("GDPR").</p>

                    <h6 class="fw-bold mt-4">1. Data Controller</h6>
                    <p>The Data Controller is Valerio Scano, via Louis Daguerre, 22, Rome (RM) Italy. Contact email: valerioscano00@gmail.com.</p>

                    <h6 class="fw-bold mt-4">2. Personal Data We Collect</h6>
                    <p>We process the following categories of data:</p>
                    <ul>
                        <li><strong>Registration data:</strong> first name, last name, email address, and password (stored exclusively in encrypted/hashed form).</li>
                        <li><strong>Usage and technical data:</strong> for example IP address, date and time of access, browser type, and technical logs, collected for the operation and security of the Service.</li>
                    </ul>
                    <p>We do not process special categories of data (such as health data, beliefs, or biometric data).</p>

                    <h6 class="fw-bold mt-4">3. Purposes of Processing and Legal Bases</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Purpose</th>
                                    <th>Legal basis (Art. 6 GDPR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Creating and managing your account; providing the Service</td>
                                    <td>Performance of a contract (Art. 6(1)(b))</td>
                                </tr>
                                <tr>
                                    <td>Sending service notifications (e.g. confirmation that a resource was successfully added, account-related messages)</td>
                                    <td>Performance of a contract (Art. 6(1)(b))</td>
                                </tr>
                                <tr>
                                    <td>Security, abuse prevention, and technical logging</td>
                                    <td>Legitimate interest of the Controller (Art. 6(1)(f))</td>
                                </tr>
                                <tr>
                                    <td>Compliance with legal obligations</td>
                                    <td>Legal obligation (Art. 6(1)(c))</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p>We do <strong>not</strong> send promotional or marketing emails, and we do not carry out profiling. The emails you receive are strictly functional to the Service you signed up for.</p>
                    <p>Providing your registration data is necessary to use the Service: without it, an account cannot be created.</p>

                    <h6 class="fw-bold mt-4">4. How We Process and Protect Your Data</h6>
                    <p>Data is processed using electronic tools, with appropriate technical and organisational measures to ensure its security, including encrypted connections (HTTPS), hashing of passwords, and restricted access to data.</p>

                    <h6 class="fw-bold mt-4">5. Recipients and Data Processors</h6>
                    <p>We do not sell your data, nor do we share it with third parties for their own purposes. However, to operate the Service we rely on providers that process data on our behalf as <strong>data processors</strong>:</p>
                    <ul>
                        <li><strong>Hosting / infrastructure:</strong> Laravel Cloud (United States, running on Amazon Web Services).</li>
                        <li><strong>Email delivery:</strong> Resend (United States).</li>
                    </ul>
                    <p>These providers process your data solely to deliver the services we have contracted them for, under a data processing agreement.</p>

                    <h6 class="fw-bold mt-4">6. International Data Transfers</h6>
                    <p>Because our hosting provider (Laravel Cloud, on AWS) and our email provider (Resend) are based in the <strong>United States</strong>, your personal data may be transferred to and processed in the United States.</p>
                    <p>Such transfers are carried out under the safeguards required by Chapter V of the GDPR — for example, the European Commission's <strong>Standard Contractual Clauses</strong> and/or the providers' certification under the <strong>EU–US Data Privacy Framework</strong>, together with supplementary measures where appropriate. You may request a copy of the applicable safeguards by contacting us at valerioscano00@gmail.com.</p>

                    <h6 class="fw-bold mt-4">7. Data Retention</h6>
                    <ul>
                        <li><strong>Account data.</strong></li>
                        <li><strong>Technical logs.</strong></li>
                        <li><strong>Data subject to legal/accounting retention:</strong> kept for the period required by law.</li>
                    </ul>

                    <h6 class="fw-bold mt-4">8. Cookies</h6>
                    <p>This Service uses <strong>only technical cookies</strong> strictly necessary for it to function. We do not use profiling cookies or third-party cookies, so <strong>no consent is required</strong>. The cookies we set are:</p>
                    <ul>
                        <li><code>laravel_session</code> — keeps your session active while you browse (session management).</li>
                        <li><code>XSRF-TOKEN</code> — protects forms against CSRF attacks (security).</li>
                        <li><code>remember_web_*</code> — keeps you logged in if you choose "Remember me" (persistent).</li>
                    </ul>
                    <p>These cookies do not track you and are not shared with third parties.</p>

                    <h6 class="fw-bold mt-4">9. Your Rights</h6>
                    <p>At any time you may exercise the rights granted by Articles 15–22 of the GDPR, namely to:</p>
                    <ul>
                        <li>access your data and obtain a copy of it;</li>
                        <li>request its rectification or update;</li>
                        <li>request its erasure ("right to be forgotten");</li>
                        <li>request restriction of processing, or object to processing based on legitimate interest;</li>
                        <li>obtain the portability of the data you provided;</li>
                        <li>where consent was given, withdraw it without affecting the lawfulness of prior processing.</li>
                    </ul>
                    <p>To exercise these rights, write to valerioscano00@gmail.com. We will respond within the time limits set by law.</p>

                    <h6 class="fw-bold mt-4">10. Right to Lodge a Complaint</h6>
                    <p>You have the right to lodge a complaint with the Italian supervisory authority, the <strong>Garante per la protezione dei dati personali</strong> (www.garanteprivacy.it), if you believe that the processing of your data infringes applicable law.</p>

                    <h6 class="fw-bold mt-4">11. Changes to This Policy</h6>
                    <p>This Privacy Policy may be updated. Changes will be published on this page together with the date of the latest update.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
