@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title"> Master Menu </h3>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Menu Makanan & Minuman</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Menu</th>
                                    <th>Harga</th>
                                    <th>Vendor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menus as $m)
                                <tr>
                                    <td>{{ $m->nama_menu }}</td>
                                    <td>Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                                    <td>{{ $m->vendor->nama_vendor ?? 'Tidak ada vendor' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-gradient-info">Detail</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection