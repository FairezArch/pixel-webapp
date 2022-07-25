@extends('layouts.admin')

@section('title', 'Role Permission')

@section('content')
    <section class="rolepermission">
        <div class="rolepermission__header">
            <div class="rolepermission__title">
                <span>Role & Permission</span>
            </div>
            <div class="rolepermission__button">
                @can('role_permission_create')
                <a class="btn-add" href="{{route('role-permission.create')}}">Tambah Role</a>
                @endcan
            </div>
        </div>
        <div class="rolepermission__body">
            <div class="rolepermission__wrapper">
                @forelse ( $roles as  $role )
                    <div class="rolepermission__list">
                        <div class="rolepermission__list-name">
                            <span>{{$role->name}}</span>
                        </div>
                        <div class="rolepermission__list-button">
                            @can('role_permission_update')
                            <a href="{{route('role-permission.edit',['role_permission' => $role->id])}}">
                                Permission  
                            </a>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div>Tidak ada Role</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
