@extends('layouts.master')

@section('content')
  <div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-primary text-white me-2">
        <i class="mdi mdi-pencil-box"></i>
      </span>
      Edit Buku
    </h3>
  </div>

  <div class="row">
    <div class="col-8 grid-margin">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('buku.update', $buku->idbuku ?? $buku->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label>Kode</label>
              <input type="text" name="kode" class="form-control" value="{{ old('kode', $buku->kode) }}" required>
            </div>
            <div class="form-group">
              <label>Judul</label>
              <input type="text" name="judul" class="form-control" value="{{ old('judul', $buku->judul) }}" required>
            </div>
            <div class="form-group">
              <label>Pengarang</label>
              <input type="text" name="pengarang" class="form-control" value="{{ old('pengarang', $buku->pengarang) }}" required>
            </div>
            <div class="form-group">
              <label>Kategori</label>
              <select name="idkategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $k)
                  <option value="{{ $k->idkategori ?? $k->id }}" {{ (old('idkategori', $buku->idkategori ?? null) == ($k->idkategori ?? $k->id)) ? 'selected' : '' }}>{{ $k->nama_kategori ?? $k->name }}</option>
                @endforeach
              </select>
            </div>
            <button class="btn btn-primary mt-3">Simpan</button>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary mt-3">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
