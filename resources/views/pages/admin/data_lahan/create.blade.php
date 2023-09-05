@extends('layouts.backend.app')

@section('main-content')
    <div class="card">
        <div class="card-header">
            <h5>{{ $title }}</h5>
        </div>
        @if (App\Models\DataLahan::getPetani(Auth::user()->id) == null)
            <form action="{{ url('/data_lahan/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    <div class="form-group mb-3">
                        <label>Nama Lahan</label>
                        <input class="form-control" type="text" name="nama_lahan" placeholder="nama_lahan" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Data Lahan</label>
                        <input class="form-control" type="text" name="data_lahan" placeholder="data_lahan" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>luas Lahan</label>
                        <input class="form-control" type="text" name="luas_lahan" placeholder="luas_lahan" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        @else
            <div class="card-body">
                @php
                    $data_lahan = App\Models\DataLahan::where('id_user', Auth::user()->id)->first();
                @endphp
                <div class="row">
                    <div class="col-6">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Nama Lahan</strong></td>
                                <td>{{ $data_lahan->nama_lahan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Luas Lahan</strong></td>
                                <td>{{ $data_lahan->luas_lahan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Jagung</strong></td>
                                <td>{{ $data_lahan->data_lahan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Tanam</strong></td>
                                <td>{{ $data_lahan->data_lahan }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><button type="button" class="btn btn-warning" data-toggle="modal"
                                        data-target="#edit-{{ $data_lahan->id }}">Edit
                                    </button></td>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>Nama Lahan1</strong></td>
                                    <td>{{ $data_lahan->nama_lahan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Luas Lahan</strong></td>
                                    <td>{{ $data_lahan->luas_lahan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Data Lahan</strong></td>
                                    <td>{{ $data_lahan->data_lahan }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#edit-{{ $data_lahan->id }}">Edit
                                        </button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            @if ($data_lahan != null)
                @include('pages.admin.data_lahan.modal_edit')
            @endif
        @endif
    </div>

@endsection
