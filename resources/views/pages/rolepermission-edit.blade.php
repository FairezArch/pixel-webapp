@extends('layouts.admin')

@section('title', 'Edit Role Permission')

@section('content')
    <section class="rolepermission-edit">
        <div class="rolepermission-edit__header">
            <div class="rolepermission-edit__title">
                <span>Ubah Role Permission</span>
            </div>
        </div>
        <div class="rolepermission-edit__body">
            <form method="POST" action="{{ route('role-permission.update', ['role_permission' => request()->role_permission]) }}"
                class="form-edit">
                @csrf
                @method('put')
                <div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" name="name" id="name" placeholder="Role" value="{{ $role->name }}"
                            required>
                        @error('name')
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center border center_table" scope="col" style="vertical-align : middle;text-align:center;">Menu</th>
                                    <th colspan="5" class="text-center border-right" scope="col">List Permission</th>
                                </tr>
                                <tr>
                                    <td class="text-center border" style="width: 150px;" scope="col">List</td>
                                    <td class="text-center border" style="width: 150px;" scope="col">Create</td>
                                    <td class="text-center border" style="width: 150px;" scope="col">Update</td>
                                    <td class="text-center border" style="width: 150px;" scope="col">Delete</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $key => $permission)
                                <tr>
                                    <td scope="row">{{$key}}</td>
                                    @foreach($permission as $list)
                                    <td class="text-center" scope="row">
                                        <input type="checkbox" id="click" class="permission" id="permission" name="permission[]" value="{{$list->name}}" {{in_array($list->id, $permissionInRole) ? 'checked="checked"' : ''}}>
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="form-button">
                        <a href="{{ route('role-permission.index') }}">Batal</a>
                        <button type="submit">Ubah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
