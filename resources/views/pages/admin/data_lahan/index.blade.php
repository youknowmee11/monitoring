@extends('layouts.backend.app')

@section('main-content')
    @include('layouts.backend.alert')
    <div class="card">
        <div class="card-header">
            <h5>{{ $title }}</h5>
        </div>
        <div class="card-body">
            <div class="my-2">
                <a href="#" class="btn btn-primary  " data-toggle="modal" data-target="#create">Tambah Lahan </a>
            </div>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Nama Lahan</th>
                        <th>Luas Lahan</th>
                        <th>Jenis Jagung</th>
                        <th>Terakhir Tanam</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($petani as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_lahan }}</td>
                            <td>{{ $item->luas_lahan }}</td>
                            <td>{{ $item->jenis_jagung }}</td>
                            <td>{{ $item->terakhir_tanam }}</td>
                            {{-- <td>{{ App\Models\DataLahan::getPetani($item->id) != null ? 'Ada' : 'Belum diisi' }}</td> --}}
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
