@extends('layouts.app')

@section('title', 'House Owners')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>House Owners</h2>
    <a href="/admin/house-owners" class="btn btn-primary">Add New Owner</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($owners as $owner)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $owner->name }}</td>
            <td>{{ $owner->email }}</td>
            <td>
                <a href="/admin/house-owners" class="btn btn-warning btn-sm">Edit</a>
                <a href="/admin/house-owners" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
