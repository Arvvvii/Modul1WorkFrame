@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="mdi mdi-receipt"></i>
            </span> Data Transaksi
        </h3>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-success">Riwayat Pesanan (Status: Lunas)</h4>
                    <p class="card-description"> Menampilkan pesanan yang berhasil dibayar oleh Customer </p>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">ID Pesanan</th>
                                    <th>Nama Customer</th>
                                    <th class="text-end">Total Bayar</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Waktu Transaksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksi as $t)
                                <tr>
                                    <td class="text-center">#{{ $t->idpesanan }}</td>
                                    <td class="ps-3">{{ $t->nama }}</td>
                                    <td class="text-end font-weight-bold">
                                        Rp {{ number_format($t->total, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if($t->status_bayar == 1)
                                            <label class="badge badge-success px-3">LUNAS</label>
                                        @else
                                            <label class="badge badge-warning px-3">PENDING</label>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ date('d/m/Y H:i', strtotime($t->created_at)) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="mdi mdi-information-outline text-info" style="font-size: 2rem;"></i>
                                        </div>
                                        <p class="mb-0 text-muted">Belum ada data pesanan dengan status lunas.</p>
                                    </td>
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