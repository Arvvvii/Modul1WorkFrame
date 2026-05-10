@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title"> Data Toko </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Master</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Toko</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-radius: 15px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title mb-0">Daftar Toko</h4>
                <a href="{{ route('toko.create') }}" class="btn btn-gradient-primary btn-fw">Tambah Toko</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Barcode</th>
                            <th>Nama Toko</th>
                            <th>Alamat</th>
                            <th>Koordinat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                        @endphp
                        @forelse($tokos as $toko)
                        <tr>
                            <td>{{ $toko->idtoko }}</td>
                            <td>
                                <div>{!! $generator->getBarcode($toko->barcode, $generator::TYPE_CODE_128, 1, 30) !!}</div>
                                <small class="text-muted">{{ $toko->barcode }}</small>
                            </td>
                            <td>{{ $toko->nama_toko }}</td>
                            <td>{{ $toko->alamat }}</td>
                            <td>
                                <small>Lat: {{ $toko->latitude }}</small><br>
                                <small>Lng: {{ $toko->longitude }}</small>
                            </td>
                            <td>
                                <a href="{{ route('toko.cetak_barcode', $toko->idtoko) }}" target="_blank" class="btn btn-sm btn-gradient-dark" title="Cetak Barcode">
                                    <i class="mdi mdi-printer"></i>
                                </a>
                                <a href="{{ route('toko.edit', $toko->idtoko) }}" class="btn btn-sm btn-gradient-info">Edit</a>
                                <form action="{{ route('toko.destroy', $toko->idtoko) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus toko ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-gradient-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data toko</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
