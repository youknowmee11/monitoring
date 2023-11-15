@extends('layouts.backend.app')

@section('main-content')
    @include('layouts.backend.alert')
    <div class="card">
        <div class="card-header">
            <h5>{{ $title }}</h5>
        </div>
        <div class="card-body">
            <div class="my-3">
                <form action="{{ route('read_all', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success"
                        {{ App\Models\Notifikasi::where('id_user', Auth::user()->id)->where('read_at', null)->count() == 0? 'disabled': '' }}>
                        <i class="fa fa-check"> </i>
                        <span>Tandai telah dibaca</span>
                    </button>
                </form>
            </div>
            <table class="table table-bordered table-hover" id="datatable">
                <thead>
                    <tr>
                        <th style="width:10px;">NO</th>
                        <th>Notifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifikasi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><small class="text-muted">{{ $item->created_at->format('d F Y , H:i:s') }}</small>
                                <br><strong>{{ $item->message }}</strong><br>
                                {!! $item->read_at == null ? '<small>Belum dibaca</small>' : '<small class="text-success">Telah dibaca</small>' !!}
                            </td>
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
