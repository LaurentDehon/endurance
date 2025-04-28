@extends('layouts.app')

@section('title', 'User Details - Admin')

@section('content')
    <livewire:admin.user-detail :userId="$userId" />
@endsection