@extends('layouts.backend.app')

@section('main-content')
    @include('layouts.backend.alert')
    <div class="card">
        <div class="card-header">
            <h5>{{ $title }}</h5>
        </div>
        <div class="card-body">
            @if (Auth::user()->role == 'petani')
                <div class="my-2">
                    <a href="#" class="btn btn-primary  " data-toggle="modal" data-target="#create">Tambah Lahan </a>
                </div>
            @endif
            <table class="table table-bordered table-hover" id="datatable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Code Alat</th>
                        <th>Nama Lahan</th>
                        <th>Luas Lahan</th>
                        <th>Jenis Jagung</th>
                        <th>Terakhir Tanam</th>
                        @if (Auth::user()->role == 'petani')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lahan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->code_alat }}</td>
                            <td>{{ $item->nama_lahan }}</td>
                            <td>{{ $item->luas_lahan }} Ha</td>
                            <td>{{ $item->jenis_jagung->jenis_jagung }}</td>
                            <td>{{ $item->terakhir_tanam }}</td>
                            @if (Auth::user()->role == 'petani')
                                <td>
                                    <a href="#" class="btn btn-warning  " data-toggle="modal"
                                        data-target="#edit-{{ $item->id }}">Edit
                                    </a>
                                </td>
                                @include('pages.admin.data_lahan.modal_edit')
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td>Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('pages.admin.data_lahan.modal_create')
@endsection
