@extends('layouts.master')

@section('content')
  <div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-primary text-white me-2">
        <i class="mdi mdi-plus-box"></i>
      </span>
      Tambah Kategori
    </h3>
  </div>

  <div class="row">
    <div class="col-6 grid-margin">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <div class="form-group">
              <label>Nama Kategori</label>
              <input type="text" name="nama_kategori" class="form-control" value="{{ old('nama_kategori') }}" required>
            </div>
            <button class="btn btn-primary mt-3">Simpan</button>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary mt-3">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
