@extends('admin.layouts.default')
@section('title')
    Поля
@endsection

@section('content')
    @if(session('created'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 3000
            })
        </script>
    @endif


    <div class="row " bis_skin_checked="1">
        <div class="col-12 grid-margin" bis_skin_checked="1">
            <div class="card" bis_skin_checked="1">
                <div class="card-body" bis_skin_checked="1">
                    <div style="display: flex; justify-content: space-between; align-items: center">
                        <h4 class="card-title">Поля</h4>
                        <a href="{{route('create_fild_page')}}" style="display: flex; align-items: center; justify-content: center" class="btn btn-inverse-warning btn-fw">Добавить</a>
                    </div>
                    <div class="table-responsive" bis_skin_checked="1">
                        <table class="table">
                            <thead>
                            <tr>
                                <th> Названия</th>
                            </tr>
                            </thead>
                            @foreach($get as $item)
                                <tbody>
                                <tr>
                                    <td> {{$item->name}} </td>
                                    <td style="display: flex; justify-content: flex-end">
                                        <a href="{{route('single_page_fild', $item->id)}}" class="btn btn-inverse-warning btn-fw" bis_skin_checked="1">Просмотреть</a>
                                    </td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection