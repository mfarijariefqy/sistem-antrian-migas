@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Peserta</h3>
    <form action="{{ route('peserta.store') }}" method="POST">
        @csrf
        @include('peserta.form')
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
