@extends('admin.layouts.default')
@section('title')
    Виды спорта
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

    <div class="col-12 grid-margin stretch-card" bis_skin_checked="1">
        <div class="card" bis_skin_checked="1">
            <div class="card-body" bis_skin_checked="1">
                <h4 class="card-title">Добавить  Вид спорта</h4>
                <form  action="{{route('update_sport')}}" method="post" id="form_country" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Название</label>
                        <input style="color: white" type="text" class="form-control" id="exampleInputName1" value="{{$get->name}}" placeholder="Название" name="name">
                    </div>
                    <input type="hidden" name="sport_id" value="{{$get->id}}">
                    <div bis_skin_checked="1">
                        <img style="object-fit: cover; object-position: center; max-height: 200px; max-width: 200px; width: 100%;" src="{{asset('uploads/'. $get->photo)}}" alt="image" id="blahas">
                        <br>
                        <input accept="image/*" style="display: none" name="photo" id="file-logos" class="btn btn-outline-success" type="file">
                        <br>
                        <label style="width: 200px" for="file-logos" class="custom-file-upload btn btn-outline-success">
                            Фото для спорта
                        </label>
                    </div>
                    <br>
                    <br>

                    <div style="display: flex; justify-content: space-between;">
                    <button type="submit" class="btn btn-inverse-success btn-fw">Добавить</button>
                    <a href="{{route('delete_sport', $get->id)}}" class="btn btn-inverse-danger btn-fw">Удалить</a>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection