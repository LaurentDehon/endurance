@extends('layouts.app')
@section('content')
    @livewire('calendar', ['year' => $year ?? null])
@endsection