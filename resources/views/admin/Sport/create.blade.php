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
                <form  action="{{route('create_sport')}}" method="post" id="form_country" class="forms-sample" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" bis_skin_checked="1">
                        <label for="exampleInputName1">Название</label>
                        <input style="color: white" type="text" class="form-control" id="exampleInputName1" placeholder="Название" name="name">
                    </div>
                    <div bis_skin_checked="1">
                        <img style="object-fit: cover; object-position: center; max-height: 200px; max-width: 200px; width: 100%;" src="" alt="image" id="blahas">
                        <br>
                        <input accept="image/*" style="display: none" name="photo" id="file-logos" class="btn btn-outline-success" type="file">
                        <br>
                        <label style="width: 200px" for="file-logos" class="custom-file-upload btn btn-outline-success">
                            Фото для спорта
                        </label>
                    </div>
                    <br>
                    <br>

                    <button type="submit" class="btn btn-inverse-success btn-fw">Добавить</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $('#form_country').on('submit', function (event ) {
            event.preventDefault()
            var inputValue = $('#file-logos').val(); // Get the value of the input field

            if (inputValue !== "") {
                $('#form_country').submit();
            } else {

                alert("Пожалуста Добавте  Фото для спорта");
            }
        });
    </script>
@endsection