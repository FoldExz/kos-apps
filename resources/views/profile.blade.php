@extends('layouts.template')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Profile</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Profile</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Hi, {{ Auth::user()->username }}!</h2>
        <p class="section-lead">
        </p>

        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-5">
                <div class="card profile-widget">
                    <div class="profile-widget-header">                     
                        <img alt="image" src="{{ asset('stisla/assets/img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture">
                    </div>
                    <div class="profile-widget-description">
                        <div class="profile-widget-name">
                            {{ Auth::user()->pengelola?->nama ?? Auth::user()->penyewa?->nama ?? Auth::user()->username }} 
                            <div class="text-muted d-inline font-weight-normal"><div class="slash"></div> {{ ucfirst(Auth::user()->role) }}</div>
                        </div>
                        Selamat datang di halaman profil. Kamu login sebagai <b>{{ Auth::user()->role }}</b>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection