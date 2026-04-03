@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-info text-white me-2">
                <i class="mdi mdi-cash-multiple"></i>
            </span> Pembayaran Customer
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-success">Riwayat Pembayaran (Terbaru)</h4>
                    <p class="card-description">Menampilkan 5 transaksi terakhir (simulasi Guest)</p>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">ID Pesanan</th>
                                    <th>Nama</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksi as $t)
                                <tr>
                                    <td class="text-center">#{{ $t->idpesanan }}</td>
                                    <td class="ps-3">{{ $t->nama }}</td>
                                    <td class="text-end">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($t->status_bayar == 1)
                                            <label class="badge badge-success px-3">LUNAS</label>
                                        @else
                                            <label class="badge badge-warning px-3">PENDING</label>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $t->timestamp }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Belum ada transaksi.</td>
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
