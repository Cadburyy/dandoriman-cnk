@extends('layouts.app')

@section('content')

<style>
    body, html {
        overflow-x: hidden;
        overflow-y: auto;
    }

    .card-link-hover:hover .card {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .card-link-hover .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .card {
        border-radius: 1rem;
        border: 1px solid var(--border);
    }

    .card-header {
        border-bottom: 1px solid var(--border);
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .text-primary-dark {
        color: #0056b3;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: var(--text);
    }
</style>

<div class="container py-3">
    <h2 class="text-center mb-2"><strong>Settings Dashboard ⚙️</strong></h2>
    
    @role('Admin')
    <div class="text-center section-title">CMS Settings</div>
    <div class="row row-cols-1 row-cols-md-3 g-3 justify-content-center mt-1">
        <div class="col-md-4">
            <a href="{{ route('settings.appearance') }}" class="text-decoration-none card-link-hover">
                <div class="card h-100 text-center shadow-sm p-3">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-paint-brush fa-3x mb-2 text-danger"></i>
                        <h5 class="card-title"><strong>Appearance</strong></h5>
                        <p class="card-text text-muted"><strong>Memodifikasi tampilan perusahaan.</strong></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endrole

    <div class="text-center section-title">System Settings</div>
    <div class="row row-cols-1 row-cols-md-3 g-3 justify-content-center mt-1">
        <div class="col-md-4">
            <a href="{{ route('users.index') }}" class="text-decoration-none card-link-hover">
                <div class="card h-100 text-center shadow-sm p-3">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-users fa-3x mb-2 text-primary-dark"></i>
                        <h5 class="card-title"><strong>Manage Users</strong></h5>
                        <p class="card-text text-muted"><strong>Melihat dan mengelola semua akun pengguna.</strong></p>
                    </div>
                </div>
            </a>
        </div>

        @role('Admin')
        <div class="col-md-4">
            <a href="{{ route('roles.index') }}" class="text-decoration-none card-link-hover">
                <div class="card h-100 text-center shadow-sm p-3">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-user-tag fa-3x mb-2 text-success"></i>
                        <h5 class="card-title"><strong>Manage Roles</strong></h5>
                        <p class="card-text text-muted"><strong>Menetapkan dan memodifikasi peran dan izin pengguna.</strong></p>
                    </div>
                </div>
            </a>
        </div>
        @endrole

        <div class="col-md-4">
            <a href="{{ route('customers.index') }}" class="text-decoration-none card-link-hover">
                <div class="card h-100 text-center shadow-sm p-3">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-handshake fa-3x mb-2 text-info"></i>
                        <h5 class="card-title"><strong>Manage Customers</strong></h5>
                        <p class="card-text text-muted"><strong>Menambahkan dan memodifikasi customer.</strong></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
