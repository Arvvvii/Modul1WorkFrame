@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h3 class="page-title"> Master Vendor </h3>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Vendor Kantin</h4>
                    <p class="card-description"> Kelola data penyedia makanan </p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID Vendor</th>
                                    <th>Nama Vendor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendors as $v)
                                <tr>
                                    <td>{{ $v->idvendor }}</td>
                                    <td>{{ $v->nama_vendor }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-gradient-warning">Edit</button>
                                        <button class="btn btn-sm btn-gradient-danger">Hapus</button>
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