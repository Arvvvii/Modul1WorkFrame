@extends('layouts.master')

@section('content')
  <div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-primary text-white me-2">
        <i class="mdi mdi-format-list-bulleted"></i>
      </span>
      Kategori
    </h3>
  </div>

  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title d-flex justify-content-between align-items-center">Daftar Kategori
            <a href="{{ route('kategori.create') }}" class="btn btn-sm btn-primary">Tambah Data</a>
          </h4>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($kategoris as $kat)
                  <tr>
                    <td>{{ $kat->idkategori ?? $kat->id }}</td>
                    <td>{{ $kat->nama_kategori ?? $kat->name ?? '-' }}</td>
                    <td>
                      <a href="{{ route('kategori.edit', $kat->idkategori ?? $kat->id) }}" class="btn btn-sm btn-warning">Edit</a>
                      <form action="{{ route('kategori.destroy', $kat->idkategori ?? $kat->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                      </form>
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

@endsection
