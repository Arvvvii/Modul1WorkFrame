@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-multiple"></i>
            </span> Data Customer
        </h3>
        <nav aria-label="breadcrumb">
            <a href="{{ route('customer.create') }}" class="btn btn-gradient-primary btn-icon-text">
                <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Customer 
            </a>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Pelanggan</h4>
                    <p class="card-description">Daftar lengkap customer beserta snapshot foto</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="bg-light">
                                <tr>
                                    <th> Foto </th>
                                    <th> Nama </th>
                                    <th> Alamat </th>
                                    <th> Wilayah </th>
                                    <th> Kodepos </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                <tr>
                                    <td class="py-1">
                                        @if($customer->foto_path)
                                            <img src="{{ asset('storage/' . $customer->foto_path) }}" alt="image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"/>
                                        @else
                                            <div class="badge badge-secondary">No Image</div>
                                        @endif
                                    </td>
                                    <td class="fw-bold"> {{ $customer->nama }} </td>
                                    <td class="text-wrap" style="max-width: 200px;"> {{ $customer->alamat }} </td>
                                    <td> 
                                        <small>
                                            {{ $customer->province->name ?? '-' }}<br>
                                            {{ $customer->regency->name ?? '-' }}
                                        </small>
                                    </td>
                                    <td> {{ $customer->postal_code ?? '-' }} </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">Belum ada data customer.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection