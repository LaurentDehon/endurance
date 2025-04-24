@extends('layouts.app')
@section('content')
    @livewire('user-detail', ['userId' => $userId])
@endsection