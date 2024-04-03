@extends('admin.layouts.default')
@section('title')
    Города
@endsection

<style>
    input{
        color: white !important;
    }
</style>

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

    <div class="col-12 grid-margin stretch-card" bis_skin_checked="1">
        <div class="card" bis_skin_checked="1">
            <div class="card-body" bis_skin_checked="1">
                <h4 class="card-title">Добавить Город</h4>
                <form  action="{{route('create_city')}}" method="post" class="forms-sample">
                    @csrf
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Название</label>
                        <input type="text" class="form-control" id="exampleInputName1" placeholder="Название" name="name">
                    </div>
                    <button type="submit" class="btn btn-inverse-success btn-fw">Добавить</button>
                </form>
            </div>
        </div>
    </div>
@endsection