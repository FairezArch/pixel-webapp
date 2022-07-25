@extends('layouts.admin')

@section('title', 'Add Role Permission')

@section('content')
    <section class="rolepermission-create">
        <div class="rolepermission-create__header">
            <div class="rolepermission-create__title">
                <span>Tambah Role Permission</span>
            </div>
        </div>

        <div class="rolepermission-create__body">
            <form method="POST" action="{{ route('role-permission.store') }}" class="form-create" >
                @csrf
                <div>
                    <div class="form-group">
                        <label for="name">Nama Role</label>
                        <input type="text" name="name" id="name" placeholder="Role" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
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
                                        <input type="checkbox" id="click" class="permission" id="permission" name="permission[]" value="{{$list->name}}">
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="form-button">
                        <a href="{{ route('role-permission.index') }}">Batal</a>
                        <button type="submit">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
