@extends('layouts.backend.app')

@section('main-content')
    @include('layouts.backend.alert')
    <div class="card">
        <div class="card-header">
            <h5>{{ $title }}</h5>
        </div>
        <div class="card-body">
            <div class="my-2">
                <a href="#" class="btn btn-primary  " data-toggle="modal" data-target="#create">Tambah Alat</a>
            </div>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Code Alat</th>
                        <th>Tanggal Pembuatan</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alat as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->code_alat }}</td>
                            <td>{{ $item->tanggal_buat }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                @php
                                    $cek_digunakan = App\Models\DataLahan::where('code_alat', $item->code_alat)->count();
                                @endphp
                                @if ($cek_digunakan == 0)
                                    <span class="badge badge-secondary">Belum digunakan</span>
                                @else
                                    <span class="badge badge-primary">digunakan</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#qr-{{ $item->id }}">Qr
                                </button>
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                    data-target="#edit-{{ $item->id }}">Edit
                                </button>
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#delete-{{ $item->id }}">Hapus
                                </button>
                            </td>
                            @include('pages.admin.alat.modal_qr')
                            @include('pages.admin.alat.modal_edit')
                        </tr>
                        @include('pages.admin.alat.modal_delete')
                    @empty
                        <tr>
                            <td>Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('pages.admin.alat.modal_create')
@endsection
