@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Edit Barang</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('barang.update', $barang->id_barang) }}">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $barang->nama) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label>Harga</label>
                    <input type="number" name="harga" class="form-control" value="{{ old('harga', $barang->harga) }}" step="0.01" required>
                </div>
                <div class="d-flex">
                    <button type="submit" class="btn btn-primary mr-2">Perbarui</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
