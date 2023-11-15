@extends('layouts.backend.app')

@section('main-content')
    @include('layouts.backend.alert')
    <div class="card">
        <div class="card-header">
            <h5>{{ $title }}</h5>
        </div>
        <div class="card-body">
            <div class="my-2">
                <a href="#" class="btn btn-primary  " data-toggle="modal" data-target="#create">Tambah Jenis</a>
            </div>
            <table class="table table-bordered table-hover" id="datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Jagung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jenis_jagung as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->jenis_jagung }}</td>

                            <td>
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                    data-target="#edit-{{ $item->id }}">Edit
                                </button>
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#delete-{{ $item->id }}">Hapus
                                </button>
                            </td>
                            @include('pages.admin.jenis_jagung.modal_edit')
                        </tr>
                        @include('pages.admin.jenis_jagung.modal_delete')
                    @empty
                        <tr>
                            <td>Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('pages.admin.jenis_jagung.modal_create')
@endsection
