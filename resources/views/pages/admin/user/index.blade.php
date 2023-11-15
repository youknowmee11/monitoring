@extends('layouts.backend.app')

@section('main-content')
    @include('layouts.backend.alert')
    <div class="card">
        <div class="card-header">
            <h5>{{ $title }}</h5>
        </div>
        <div class="card-body">
            <div class="my-2">
                <a href="#" class="btn btn-primary  " data-toggle="modal" data-target="#create">tambah admin </a>
            </div>
            <table class="table table-bordered table-hover" id="datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>email</th>
                        <th>Validasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name . ' ' . $item->last_name }} </td>
                            <td>{{ $item->email }}</td>

                            <td>
                                @if ($item->active == 0)
                                    <form action="{{ url('/validasi', $item->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="active" value="1">
                                        <button type="submit" class="btn btn-primary">Validasi</button>
                                    </form>
                                @else
                                    <span class="badge badge-primary">Tervalidasi</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                    data-target="#edit-{{ $item->id }}">Edit
                                </button>
                                @if (Auth::user()->id != $item->id)
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#delete-{{ $item->id }}">Hapus
                                    </button>
                                @endif
                                @include('pages.admin.user.modal')

                            </td>
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
    @include('pages.admin.user.modal_create')
@endsection
