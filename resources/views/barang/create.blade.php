@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Barang</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('barang.store') }}">
                @csrf
                <div class="form-group mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label>Harga</label>
                    <input type="number" name="harga" class="form-control" value="{{ old('harga', 0) }}" step="0.01" required>
                </div>
                <div class="d-flex">
                    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
