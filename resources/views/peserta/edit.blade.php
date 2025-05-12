@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Peserta</h3>
    <form action="{{ route('peserta.update', $peserta->id) }}" method="POST">
        @csrf @method('PUT')
        @include('peserta.form', ['peserta' => $peserta])
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
